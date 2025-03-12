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

        if ($user->hasRole('super_admin')) {
            $expenses = Expense::with('category', 'rt')
                ->when($rtId, function ($query) use ($rtId) {
                    return $query->where('rts_id', $rtId);
                })->latest()->get();
        } else {
            $expenses = Expense::where('rts_id', $user->rt_id)
                ->with('category')
                ->latest()
                ->get();
        }

        $categories = Category::all();
        $rts = RT::all();

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
        $rtId = $user->hasRole('super_admin') ? $request->input('rts_id') : $user->rt_id;

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
        $expense->update($request->all());

        return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
