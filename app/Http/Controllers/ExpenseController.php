<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $categories = Category::all();

        // Ambil bulan dan tahun dari request (jika ada)
        $month = $request->input('month');
        $year = $request->input('year');

        // Query pengeluaran dengan filter RT dan tanggal jika ada
        $query = Expense::where('rts_id', $user->rts_id);

        if ($month && $year) {
            $query->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month);
        }

        $expenses = $query->latest()->get();
        $totalExpense = $expenses->sum('amount');

        // Ambil semua data pemasukan untuk RT ini
        $incomes = Income::where('rts_id', $user->rts_id)->latest()->get();
        $totalIncome = $incomes->sum('amount');

        // Hitung total saldo
        $totalBalance = $totalIncome - $totalExpense;

        return view('finance.admin-rt.expense', compact('expenses', 'incomes', 'categories', 'totalExpense', 'totalBalance', 'totalIncome'));
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
        Expense::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'rts_id' => $user->rts_id,
        ]);
        
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $expense = Expense::where('id', $id)
                          ->where('rts_id', auth()->user()->rts_id)
                          ->firstOrFail();

        return response()->json($expense); // Jika menggunakan modal AJAX
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
        $expense = Expense::where('id', $id)->where('rts_id', auth()->user()->rts_id)->firstOrFail();
        $expense->update($request->only(['name', 'category_id', 'amount', 'description', 'transaction_date']));

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    /**
     * Hapus dengan Soft Delete
     */
    public function destroy($id)
    {
        $expense = Expense::where('id', $id)
                          ->where('rts_id', auth()->user()->rts_id)
                          ->firstOrFail();
        $expense->delete(); // Soft delete

        return response()->json(['success' => true, 'message' => 'Data pengeluaran berhasil dihapus!']);
    }

    /**
     * Menampilkan data yang sudah dihapus (Soft Deleted)
     */
    public function trashed()
    {
        $expenses = Expense::onlyTrashed()
                            ->where('rts_id', auth()->user()->rts_id)
                            ->get();

        return view('finance.admin-rt.trashed-expense', compact('expenses'));
    }

    /**
     * Mengembalikan Data yang Terhapus (Restore)
     */
    public function restore($id)
    {
        $expense = Expense::onlyTrashed()
                          ->where('id', $id)
                          ->where('rts_id', auth()->user()->rts_id)
                          ->firstOrFail();

        $expense->restore(); // Mengembalikan data

        return redirect()->route('expenses.trashed')->with('success', 'Data berhasil dikembalikan!');
    }

    /**
     * Menghapus Permanen Data (Force Delete)
     */
    public function forceDelete($id)
    {
        $expense = Expense::onlyTrashed()
                          ->where('id', $id)
                          ->where('rts_id', auth()->user()->rts_id)
                          ->firstOrFail();

        $expense->forceDelete(); // Hapus permanen

        return redirect()->route('expenses.trashed')->with('success', 'Data berhasil dihapus secara permanen!');
    }
}
