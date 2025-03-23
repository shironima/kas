<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RT;
use Carbon\Carbon;
use App\Models\Income;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Income::sum('amount');
        $totalExpense = Expense::sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyIncome = Income::where('created_at', '>=', $currentMonth)->sum('amount');
        $monthlyExpense = Expense::where('created_at', '>=', $currentMonth)->sum('amount');

        $totalTransactions = Income::count() + Expense::count();
        $incomeExpenseRatio = $monthlyExpense > 0 ? round(($monthlyIncome / $monthlyExpense) * 100, 2) : '0%';
        $totalRTs = RT::count();

        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabel = $month->format('M Y');

            $income = Income::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->sum('amount') / 1000000;

            $expense = Expense::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->sum('amount') / 1000000;

            $months[] = $monthLabel;
            $incomeData[] = round($income, 2);
            $expenseData[] = round($expense, 2);
        }

        return view('dashboard', compact(
            'totalBalance', 'monthlyIncome', 'monthlyExpense',
            'totalTransactions', 'incomeExpenseRatio', 'totalRTs',
            'months', 'incomeData', 'expenseData'
        ));
    }
}
