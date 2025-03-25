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

        $rtName = RT::find($rtsId)->name ?? 'RT Tidak Ditemukan';

        $financialData = $this->getIncomeExpenseData($rtsId);
        $chartData = $this->getChartData($rtsId);
        $recentTransactions = $this->getRecentTransactions($rtsId);

        return view('dashboard-rt', array_merge(
            compact('rtName', 'recentTransactions'),
            $financialData,
            $chartData
        ));
    }

    private function getIncomeExpenseData($rtsId)
    {
        $totalIncome = Income::where('rts_id', $rtsId)->sum('amount') ?? 0;
        $totalExpense = Expense::where('rts_id', $rtsId)->sum('amount') ?? 0;
        $saldoKas = $totalIncome - $totalExpense;

        return compact('totalIncome', 'totalExpense', 'saldoKas');
    }

    private function getChartData($rtsId)
    {
        $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->format('M Y'))->toArray();

        $incomeData = Income::where('rts_id', $rtsId)
            ->whereBetween('transaction_date', [now()->subMonths(6), now()])
            ->selectRaw("DATE_FORMAT(transaction_date, '%b %Y') as month, SUM(amount) as total")
            ->groupBy('month')->orderBy('month')->pluck('total', 'month')->toArray();

        $expenseData = Expense::where('rts_id', $rtsId)
            ->whereBetween('transaction_date', [now()->subMonths(6), now()])
            ->selectRaw("DATE_FORMAT(transaction_date, '%b %Y') as month, SUM(amount) as total")
            ->groupBy('month')->orderBy('month')->pluck('total', 'month')->toArray();

        $chartIncome = array_map(fn($month) => $incomeData[$month] ?? 0, $months);
        $chartExpense = array_map(fn($month) => $expenseData[$month] ?? 0, $months);

        return compact('months', 'chartIncome', 'chartExpense');
    }

    private function getRecentTransactions($rtsId)
    {
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

        return $latestIncomes->merge($latestExpenses)->sortByDesc('date');
    }


}
