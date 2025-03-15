<?php

namespace App\Exports;

use App\Models\Income;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinanceReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $selectedRTs;

    public function __construct($startDate, $endDate, $selectedRTs)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->selectedRTs = $selectedRTs;
    }

    public function collection()
    {
        $incomes = Income::whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->when(!empty($this->selectedRTs), function ($query) {
                return $query->whereIn('rts_id', $this->selectedRTs);
            })
            ->get();

        $expenses = Expense::whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->when(!empty($this->selectedRTs), function ($query) {
                return $query->whereIn('rts_id', $this->selectedRTs);
            })
            ->get();

        return $incomes->concat($expenses);
    }

    public function headings(): array
    {
        return ["ID", "Nama", "Kategori", "Jumlah", "Keterangan", "RT", "Tanggal"];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            optional($row->category)->name,
            number_format($row->amount, 2),
            $row->description,
            optional($row->rt)->name,
            $row->transaction_date,
        ];
    }
}
