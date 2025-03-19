<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;

class DashboardRTController extends Controller
{
    public function index()
    {
        // Ambil total pemasukan & pengeluaran
        $totalIncome = Income::sum('amount');
        $totalExpense = Expense::sum('amount');

        // Ambil data pemasukan & pengeluaran untuk chart (group by bulan)
        $monthlyIncome = Income::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyExpense = Expense::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('dashboard-rt', compact('totalIncome', 'totalExpense', 'monthlyIncome', 'monthlyExpense'));
    }
}
