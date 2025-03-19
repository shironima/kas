<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;

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

        // Query dengan filter
        $query = Income::where('rts_id', $user->rts_id);

        if ($month && $year) {
            $query->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month);
        }

        $incomes = Income::where('rts_id', $user->rts_id)->latest()->get(); 
    
        return view('finance.admin-rt.income', compact('incomes', 'categories'));
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

        return redirect()->route('income.index')->with('success', 'Data pemasukan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Pastikan hanya menghapus data dari RT yang sesuai
        $income = Income::where('id', $id)->where('rts_id', auth()->user()->rts_id)->firstOrFail();
        $income->delete();

        return redirect()->route('income.index')->with('success', 'Data pemasukan berhasil dihapus!');
    }
}
