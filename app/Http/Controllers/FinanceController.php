<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Income;
use App\Models\Expense;
use App\Models\RT;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceReportExport;

class FinanceController extends Controller
{
    public function index(Request $request)
{
    $selectedRT = $request->rt_id; // Filter RT
    $selectedCategory = $request->category_id; // Filter Kategori
    $tab = $request->tab ?? 'income'; // Default tab pemasukan

    // Query Pemasukan
    $incomesQuery = Income::with('category', 'rt');
    if ($selectedRT) {
        $incomesQuery->where('rts_id', $selectedRT);
    }
    if ($selectedCategory) {
        $incomesQuery->where('category_id', $selectedCategory);
    }
    $incomes = $incomesQuery->latest()->get();
    $totalIncome = $incomes->sum('amount');

    // Query Pengeluaran
    $expensesQuery = Expense::with('category', 'rt');
    if ($selectedRT) {
        $expensesQuery->where('rts_id', $selectedRT);
    }
    if ($selectedCategory) {
        $expensesQuery->where('category_id', $selectedCategory);
    }
    $expenses = $expensesQuery->latest()->get();
    $totalExpense = $expenses->sum('amount');

    // Ambil daftar RT dan Kategori untuk dropdown
    $rts = RT::all();
    $categories = Category::all();

    return view('finance.index', compact(
        'totalIncome', 'totalExpense', 'incomes', 'expenses',
        'rts', 'categories', 'selectedRT', 'selectedCategory', 'tab'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'rts_id' => 'nullable|exists:rts,id',
            'transaction_date' => 'required|date',
        ]);

        Income::create($request->all());

        return redirect()->back()->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'rts_id' => 'nullable|exists:rts,id',
            'transaction_date' => 'required|date',
        ]);

        $income = Income::findOrFail($id);
        $income->update($request->all());

        return redirect()->back()->with('success', 'Data pemasukan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $income = Income::findOrFail($id);
        $income->delete();

        return redirect()->back()->with('success', 'Data pemasukan berhasil dihapus.');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        // Ambil data sesuai rentang waktu
        $incomes = Income::whereBetween('transaction_date', [$request->start_date, $request->end_date])->get();
        $expenses = Expense::whereBetween('transaction_date', [$request->start_date, $request->end_date])->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $totalSaldo = $totalIncome - $totalExpense;

        $periode = Carbon::parse($request->start_date)->format('d M Y') . " - " . Carbon::parse($request->end_date)->format('d M Y');

        return view('finance.reports.index', compact('incomes', 'expenses', 'totalIncome', 'totalExpense', 'totalSaldo', 'periode'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new FinanceReportExport($request->start_date, $request->end_date), 'Laporan-Keuangan.xlsx');
    }
}
