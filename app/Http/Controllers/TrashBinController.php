<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;

class TrashBinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Hanya mengambil data yang sesuai dengan rts_id user
        $trashedIncomes = Income::onlyTrashed()->where('rts_id', $user->rts_id)->get();
        $trashedExpenses = Expense::onlyTrashed()->where('rts_id', $user->rts_id)->get();

        return view('finance.admin-rt.partials.trash-bin', compact('trashedIncomes', 'trashedExpenses'));
    }

    public function restore($type, $id)
    {
        $user = auth()->user();

        if ($type === 'income') {
            $income = Income::onlyTrashed()->where('id', $id)->where('rts_id', $user->rts_id)->first();
            if (!$income) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
            $income->restore();
        } elseif ($type === 'expense') {
            $expense = Expense::onlyTrashed()->where('id', $id)->where('rts_id', $user->rts_id)->first();
            if (!$expense) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
            $expense->restore();
        }

        return back()->with('success', 'Data berhasil dipulihkan!');
    }

    public function forceDelete($type, $id)
    {
        $user = auth()->user();

        if ($type === 'income') {
            $income = Income::onlyTrashed()->where('id', $id)->where('rts_id', $user->rts_id)->first();
            if (!$income) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
            $income->forceDelete();
        } elseif ($type === 'expense') {
            $expense = Expense::onlyTrashed()->where('id', $id)->where('rts_id', $user->rts_id)->first();
            if (!$expense) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
            $expense->forceDelete();
        }

        return back()->with('success', 'Data berhasil dihapus permanen!');
    }
}
