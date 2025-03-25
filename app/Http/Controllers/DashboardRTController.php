<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use App\Models\RT;

class DashboardRTController extends Controller
{
    public function dashboardRT()
    {
        $adminRT = Auth::user();
        $rtsId = $adminRT->rts_id;

        if (!$rtsId) {
            return redirect()->route('home')->with('error', 'Anda belum terhubung dengan RT mana pun.');
        }

        $rt = RT::find($rtsId);
        $rtName = $rt ? $rt->name : 'RT Tidak Ditemukan';

        // Total pemasukan & pengeluaran
        $totalIncome = Income::where('rts_id', $rtsId)->sum('amount') ?? 0;
        $totalExpense = Expense::where('rts_id', $rtsId)->sum('amount') ?? 0;
        $saldoKas = $totalIncome - $totalExpense;

        // Data pemasukan & pengeluaran per bulan (6 bulan terakhir) untuk Bar Chart
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->format('M Y');
        })->toArray();

        $incomeData = Income::where('rts_id', $rtsId)
            ->whereBetween('transaction_date', [now()->subMonths(6), now()])
            ->selectRaw("DATE_FORMAT(transaction_date, '%b %Y') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $expenseData = Expense::where('rts_id', $rtsId)
            ->whereBetween('transaction_date', [now()->subMonths(6), now()])
            ->selectRaw("DATE_FORMAT(transaction_date, '%b %Y') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartIncome = [];
        $chartExpense = [];

        foreach ($months as $month) {
            $chartIncome[] = $incomeData[$month] ?? 0;
            $chartExpense[] = $expenseData[$month] ?? 0;
        }

        // **Tambahin lagi transaksi terbaru biar gak error**
        $latestIncomes = Income::where('rts_id', $rtsId)
            ->latest()->limit(5)->get()
            ->map(fn($item) => (object) [
                'date' => $item->transaction_date,
                'name' => $item->name,
                'description' => $item->description,
                'amount' => $item->amount,
                'type' => 'income',
            ]);

        $latestExpenses = Expense::where('rts_id', $rtsId)
            ->latest()->limit(5)->get()
            ->map(fn($item) => (object) [
                'date' => $item->transaction_date,
                'name' => $item->name,
                'description' => $item->description,
                'amount' => $item->amount,
                'type' => 'expense',
            ]);

        $recentTransactions = $latestIncomes->merge($latestExpenses)->sortByDesc('date');

        return view('dashboard-rt', compact(
            'rtName', 'totalIncome', 'totalExpense', 'saldoKas',
            'months', 'chartIncome', 'chartExpense', 'recentTransactions'
        ));
    }
}
