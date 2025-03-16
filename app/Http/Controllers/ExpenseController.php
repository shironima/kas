<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use App\Models\RT;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $rtId = $request->input('rts_id');

        // Jika Super Admin, bisa melihat semua data dan filter berdasarkan RT
        if ($user->hasRole('super_admin')) {
            $expenses = Expense::with('category', 'rt')
                ->when($rtId, function ($query) use ($rtId) {
                    return $query->where('rts_id', $rtId);
                })->latest()->get();
        } 
        // Jika Admin RT, hanya bisa melihat data sesuai RT-nya sendiri
        else {
            $expenses = Expense::where('rts_id', $user->rts_id)
                ->with('category')
                ->latest()
                ->get();
        }

        $categories = Category::all();
        $rts = RT::all(); // Ambil semua RT untuk dropdown filter (hanya untuk super_admin)

        return view('finance.index', compact('expenses', 'categories', 'rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $user = auth()->user();
        $rtId = $user->hasRole('super_admin') ? $request->input('rts_id') : $user->rts_id;

        Expense::create([
            'rt_id' => $rtId,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);

        // Pastikan Admin RT hanya bisa update data miliknya
        if (auth()->user()->hasRole('admin_rt') && $expense->rt_id != auth()->user()->rts_id) {
            return redirect()->route('expense.index')->with('error', 'Anda tidak memiliki izin untuk mengubah pengeluaran ini.');
        }

        $expense->update($request->all());

        return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        // Pastikan Admin RT hanya bisa menghapus data miliknya
        if (auth()->user()->hasRole('admin_rt') && $expense->rt_id != auth()->user()->rts_id) {
            return redirect()->route('expense.index')->with('error', 'Anda tidak memiliki izin untuk menghapus pengeluaran ini.');
        }

        $expense->delete();

        return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
