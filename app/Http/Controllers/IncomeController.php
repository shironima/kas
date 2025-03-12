<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Category;
use App\Models\RT;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $rtId = $request->input('rt_id');

        if ($user->hasRole('super_admin')) {
            // Super Admin bisa melihat semua data dan bisa filter berdasarkan RT
            $incomes = Income::with('category', 'rt')
                ->when($rtId, function ($query) use ($rtId) {
                    return $query->where('rts_id', $rtId);
                })->latest()->get();
        } else {
            // Admin RT hanya bisa melihat data RT-nya sendiri
            $incomes = Income::where('rts_id', $user->rt_id)
                ->with('category')
                ->latest()
                ->get();
        }

        $categories = Category::all();
        $rts = RT::all(); // Untuk dropdown filter RT

        return view('finance.index', compact('incomes', 'categories', 'rts'));
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

        Income::create([
            'rt_id' => $rtId,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('income.index')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $income = Income::with('category')->findOrFail($id);
        $categories = Category::all();

        return view('finance.edit', compact('income', 'categories'));
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

        $income = Income::findOrFail($id);
        $income->update($request->all());

        return redirect()->route('income.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $income = Income::findOrFail($id);
        $income->delete();

        return redirect()->route('income.index')->with('success', 'Pemasukan berhasil dihapus.');
    }
}
