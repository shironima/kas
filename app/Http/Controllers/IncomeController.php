<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $categories = Category::all();

        // Ambil bulan dan tahun dari request (jika ada)
        $month = $request->input('month');
        $year = $request->input('year');

        // Query pemasukan dengan filter RT dan tanggal jika ada
        $query = Income::where('rts_id', $user->rts_id);

        if ($month && $year) {
            $query->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month);
        }

        $incomes = $query->latest()->get();
        $totalIncome = $incomes->sum('amount');

        // Ambil semua data pengeluaran untuk RT ini
        $expenses = Expense::where('rts_id', $user->rts_id)->latest()->get();
        $totalExpense = $expenses->sum('amount'); // Gunakan $expenses untuk perhitungan

        // Hitung total saldo
        $totalBalance = $totalIncome - $totalExpense;

        return view('finance.admin-rt.income', compact('incomes', 'expenses', 'categories', 'totalIncome', 'totalBalance', 'totalExpense'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Ambil user yang sedang login
        $user = auth()->user();

        // Pastikan user punya rts_id
        if (!$user->rts_id) {
            return redirect()->back()->with('error', 'RT belum terdaftar untuk akun ini!');
        }

        // Tambahkan rts_id dari user yang sedang login
        Income::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'rts_id' => $user->rts_id,
        ]);
        
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $income = Income::where('id', $id)
                        ->where('rts_id', auth()->user()->rts_id)
                        ->firstOrFail();

        return response()->json($income); // Jika menggunakan modal AJAX
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        // Pastikan hanya mengupdate data dari RT yang sesuai
        $income = Income::where('id', $id)->where('rts_id', auth()->user()->rts_id)->firstOrFail();
        $income->update($request->only(['name', 'category_id', 'amount', 'description', 'transaction_date']));

        return redirect()->route('incomes.index')->with('success', 'Data pemasukan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Pastikan hanya menghapus data dari RT yang sesuai
        $income = Income::where('id', $id)->where('rts_id', auth()->user()->rts_id)->firstOrFail();
        $income->delete();

        return response()->json(['success' => true, 'message' => 'Data pemasukan berhasil dihapus!']);
    }
}
