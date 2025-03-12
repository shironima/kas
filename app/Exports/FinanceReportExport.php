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

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Ambil data berdasarkan tanggal
        $incomes = Income::whereBetween('transaction_date', [$this->startDate, $this->endDate])->get();
        $expenses = Expense::whereBetween('transaction_date', [$this->startDate, $this->endDate])->get();

        $data = [];

        foreach ($incomes as $income) {
            $data[] = [
                'Date' => $income->transaction_date,
                'Type' => 'Income',
                'Category' => $income->category->name ?? 'N/A',
                'Amount' => $income->amount,
            ];
        }

        foreach ($expenses as $expense) {
            $data[] = [
                'Date' => $expense->transaction_date,
                'Type' => 'Expense',
                'Category' => $expense->category->name ?? 'N/A',
                'Amount' => $expense->amount,
            ];
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return ['Date', 'Type', 'Category', 'Amount'];
    }
}
