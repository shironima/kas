<?php

namespace App\Exports;

use App\Models\Income;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class FinanceReportExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $selectedRTs;

    public function __construct($startDate, $endDate, $selectedRTs = [])
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->selectedRTs = $selectedRTs;
    }

    public function collection()
    {
        // Query Pemasukan
        $incomesQuery = Income::whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        $expensesQuery = Expense::whereBetween('transaction_date', [$this->startDate, $this->endDate]);

        // Jika RT dipilih, filter berdasarkan RT yang dipilih
        if (!empty($this->selectedRTs)) {
            $incomesQuery->whereIn('rts_id', $this->selectedRTs);
            $expensesQuery->whereIn('rts_id', $this->selectedRTs);
        }

        $incomes = $incomesQuery->get();
        $expenses = $expensesQuery->get();

        $data = [];

        foreach ($incomes as $income) {
            $data[] = [
                'Date' => $income->transaction_date,
                'Type' => 'Income',
                'Category' => $income->category->name ?? 'N/A',
                'RT' => $income->rt->name ?? 'N/A',
                'Amount' => $income->amount,
            ];
        }

        foreach ($expenses as $expense) {
            $data[] = [
                'Date' => $expense->transaction_date,
                'Type' => 'Expense',
                'Category' => $expense->category->name ?? 'N/A',
                'RT' => $expense->rt->name ?? 'N/A',
                'Amount' => $expense->amount,
            ];
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return ['Date', 'Type', 'Category', 'RT', 'Amount'];
    }
}
