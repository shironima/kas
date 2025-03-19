<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use App\Models\RT;

class DashboardRTController extends Controller
{
    public function index()
    {
        $adminRT = Auth::user();
        $rtsId = $adminRT->rts_id;
        
        if (!$rtsId) {
            return redirect()->route('home')->with('error', 'Anda belum terhubung dengan RT mana pun.');
        }

        $rt = RT::find($rtsId);
        $rtName = $rt ? $rt->name : 'RT Tidak Ditemukan';

        $totalIncome = Income::where('rts_id', $rtsId)->sum('amount');
        $totalExpense = Expense::where('rts_id', $rtsId)->sum('amount');

        $monthlyIncome = Income::where('rts_id', $rtsId)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyExpense = Expense::where('rts_id', $rtsId)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $latestTransactions = collect()
            ->merge(Income::where('rts_id', $rtsId)->latest()->limit(5)->get()->map->setAttribute('type', 'income'))
            ->merge(Expense::where('rts_id', $rtsId)->latest()->limit(5)->get()->map->setAttribute('type', 'expense'))
            ->sortByDesc('created_at');

        return view('dashboard-rt', compact(
            'rtsId', 'rtName', 'totalIncome', 'totalExpense', 
            'monthlyIncome', 'monthlyExpense', 'latestTransactions'
        ));
    }
}
