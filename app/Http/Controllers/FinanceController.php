<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Income;
use App\Models\Expense;
use App\Models\RT;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Hitung Total Saldo
        $totalBalance = $totalIncome - $totalExpense;

        // Ambil daftar RT dan Kategori untuk dropdown
        $rts = RT::all();
        $categories = Category::all();

        return view('finance.index', compact(
            'totalIncome', 'totalExpense', 'totalBalance', 'incomes', 'expenses',
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
        $rts = RT::all(); // Ambil semua data RT

        return view('finance.reports.index', compact('rts'));
    }

    public function exportPdf(Request $request)
    {
        // Ambil filter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedRTs = $request->input('rts_id', []);

        // Ambil data pemasukan & pengeluaran berdasarkan filter
        $incomes = Income::whereBetween('transaction_date', [$startDate, $endDate])
                    ->when(!empty($selectedRTs), function ($query) use ($selectedRTs) {
                        return $query->whereIn('rts_id', $selectedRTs);
                    })
                    ->get();

        $expenses = Expense::whereBetween('transaction_date', [$startDate, $endDate])
                    ->when(!empty($selectedRTs), function ($query) use ($selectedRTs) {
                        return $query->whereIn('rts_id', $selectedRTs);
                    })
                    ->get();

        // Hitung total
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        // Kirim data ke view PDF
        $pdf = Pdf::loadView('finance.reports.pdf', compact('incomes', 'expenses', 'totalIncome', 'totalExpense', 'totalBalance', 'startDate', 'endDate'));

        // Download PDF
        return $pdf->download('Laporan_Keuangan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $selectedRTs = $request->rt_id ?? []; // Ambil RT yang dipilih (bisa lebih dari satu)

        return Excel::download(new FinanceReportExport($request->start_date, $request->end_date, $selectedRTs), 'Laporan-Keuangan.xlsx');
    }

}
