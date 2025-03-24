<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Income;
use App\Models\Expense;
use App\Models\RT;
use App\Models\Category;

class ReportController extends Controller
{
    function index(Request $request)
    {
        $totalIncome = Income::sum('amount');
        $totalExpense = Expense::sum('amount');
        $totalBalance = $totalIncome - $totalExpense;
        $rts = RT::all();
        $categories = Category::all();
        $tab = $request->query('tab', 'income'); // Default ke 'income'

        return view('finance.reports.index', compact('totalIncome', 'totalExpense', 'totalBalance', 'rts', 'categories', 'tab'));
        }

    // Fungsi untuk export transaksi (Income & Expense) berdasarkan filter
    public function exportPDF(Request $request)
    {
        $incomes = Income::with('rt', 'category')->get();
        $expenses = Expense::with('rt', 'category')->get();

        // Filter RT
        if ($request->filled('rts_id')) {
            $incomes->where('rts_id', $request->rts_id);
            $expenses->where('rts_id', $request->rts_id);
        }

        // Filter Bulan
        if ($request->filled('bulan')) {
            $incomes->whereMonth('transaction_date', $request->bulan);
            $expenses->whereMonth('transaction_date', $request->bulan);
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $incomes->whereYear('transaction_date', $request->tahun);
            $expenses->whereYear('transaction_date', $request->tahun);
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            if ($request->kategori == 'Income') {
                $expenses = collect([]); // Kosongkan expenses
            } elseif ($request->kategori == 'Expense') {
                $incomes = collect([]); // Kosongkan incomes
            }
        }

        $data = [
            'incomes' => $incomes,
            'expenses' => $expenses,
        ];

        $pdf = Pdf::loadView('finance.reports.transaction', $data);
        return $pdf->download('financial-report.pdf');
    }

    // ✅ Fungsi untuk export laporan analisis keuangan per RT
    public function exportRTAnalysisPDF(Request $request)
    {
        // Ambil total pemasukan per RT
        $incomeAnalysis = Income::selectRaw('rt, SUM(amount) as total_income')
            ->groupBy('rt')
            ->get();

        // Ambil total pengeluaran per RT
        $expenseAnalysis = Expense::selectRaw('rt, SUM(amount) as total_expense')
            ->groupBy('rt')
            ->get();

        // Gabungkan income & expense per RT
        $rtData = $incomeAnalysis->map(function ($income) use ($expenseAnalysis) {
            $expense = $expenseAnalysis->firstWhere('rt', $income->rt);
            return [
                'rt' => $income->rt,
                'total_income' => $income->total_income,
                'total_expense' => $expense ? $expense->total_expense : 0,
                'balance' => $income->total_income - ($expense ? $expense->total_expense : 0),
            ];
        });

        // Load PDF
        $pdf = Pdf::loadView('finance.reports.rt_analysis.pdf', compact('rtData'));

        return $pdf->download('rt-financial-analysis.pdf');
    }

    public function showReport(Request $request)
    {
        $tab = $request->query('tab', 'income'); // Default ke 'income'
        $rts = RT::all();
        $categories = Category::all();

        $reportType = $request->input('report_type', 'transaction'); 

        switch ($reportType) {
            case 'transaction':
                $incomes = Income::query();
                $expenses = Expense::query();

                // Filter berdasarkan tanggal
                if ($request->filled('start_date')) {
                    $incomes->whereDate('transaction_date', '>=', $request->start_date);
                    $expenses->whereDate('transaction_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $incomes->whereDate('transaction_date', '<=', $request->end_date);
                    $expenses->whereDate('transaction_date', '<=', $request->end_date);
                }

                // Filter berdasarkan RT
                if ($request->filled('rts_id')) {
                    $incomes->where('rt_id', $request->rts_id);
                    $expenses->where('rt_id', $request->rts_id);
                }

                $incomes = $incomes->get();
                $expenses = $expenses->get();

                $totalIncome = $incomes->sum('amount');
                $totalExpense = $expenses->sum('amount');
                $totalBalance = $totalIncome - $totalExpense;

                return view('finance.reports.index', compact(
                    'incomes', 'expenses', 'rts', 'categories', 'tab', 
                    'totalIncome', 'totalExpense', 'totalBalance'
                ));

            case 'rt_analysis':
                $incomeAnalysis = Income::selectRaw('rt_id, SUM(amount) as total_income')
                    ->groupBy('rt_id')
                    ->get();

                $expenseAnalysis = Expense::selectRaw('rt_id, SUM(amount) as total_expense')
                    ->groupBy('rt_id')
                    ->get();

                // Gabungkan income & expense per RT
                $rtData = $incomeAnalysis->map(function ($income) use ($expenseAnalysis) {
                    $expense = $expenseAnalysis->firstWhere('rt_id', $income->rt_id);
                    return [
                        'rt' => RT::find($income->rt_id)->name ?? 'Unknown',
                        'total_income' => $income->total_income,
                        'total_expense' => $expense ? $expense->total_expense : 0,
                        'balance' => $income->total_income - ($expense ? $expense->total_expense : 0),
                    ];
                });

                return view('finance.reports.rt_analysis', compact('rtData', 'rts'));

            default:
                return redirect()->route('finance.report.index');
        }
    }

}
