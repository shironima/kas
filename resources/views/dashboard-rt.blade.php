@extends('layouts.app')

@section('title', 'Dashboard Keuangan RT')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard Keuangan {{ $rtName }}</h2>

    <!-- Statistik Keuangan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $totalIncome = $totalIncome ?? 0;
            $totalExpense = $totalExpense ?? 0;
            $saldoKas = $totalIncome - $totalExpense;
        @endphp

        <!-- Total Pemasukan -->
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="bi bi-cash-stack text-blue-600 text-2xl"></i>
            </div>
            <h5 class="font-semibold text-gray-800 mt-4">Total Pemasukan</h5>
            <p class="text-gray-600 text-lg font-bold">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </p>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
            <div class="bg-red-100 p-3 rounded-full">
                <i class="bi bi-wallet2 text-red-600 text-2xl"></i>
            </div>
            <h5 class="font-semibold text-gray-800 mt-4">Total Pengeluaran</h5>
            <p class="text-gray-600 text-lg font-bold">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </p>
        </div>

        <!-- Saldo Kas RT -->
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
            <div class="bg-green-100 p-3 rounded-full">
                <i class="bi bi-bank text-green-600 text-2xl"></i>
            </div>
            <h5 class="font-semibold text-gray-800 mt-4">Saldo Kas RT</h5>
            <p class="text-gray-600 text-lg font-bold">
                Rp {{ number_format($saldoKas, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Chart Keuangan -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
        <h5 class="font-semibold text-gray-800 mb-4">Grafik Pemasukan & Pengeluaran</h5>
        <canvas id="financeChart"></canvas>
    </div>

    <!-- Daftar Transaksi Terbaru -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
        <h5 class="font-semibold text-gray-800 mb-4">Transaksi Terbaru</h5>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="py-2 px-4 border">Tanggal</th>
                        <th class="py-2 px-4 border">Jenis</th>
                        <th class="py-2 px-4 border">Keterangan</th>
                        <th class="py-2 px-4 border">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestTransactions as $transaction)
                        <tr class="text-center border-b">
                            <td class="py-2 px-4 border">{{ date('d M Y', strtotime($transaction->created_at)) }}</td>
                            <td class="py-2 px-4 border">
                                <span class="px-2 py-1 text-sm rounded 
                                    {{ $transaction->type == 'income' ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border">{{ $transaction->description }}</td>
                            <td class="py-2 px-4 border font-bold">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 px-4 text-gray-500 text-center">Belum ada transaksi terbaru</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Shortcut Input Data -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
        <a href="{{ route('incomes.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 text-center rounded-md">
            + Tambah Pemasukan
        </a>
        <a href="{{ route('expenses.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 text-center rounded-md">
            + Tambah Pengeluaran
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const incomeData = {!! json_encode($monthlyIncome->toArray() ?? array_fill(0, 12, 0)) !!};
    const expenseData = {!! json_encode($monthlyExpense->toArray() ?? array_fill(0, 12, 0)) !!};

    console.log("Data Pemasukan:", incomeData);
    console.log("Data Pengeluaran:", expenseData);

    const ctx = document.getElementById('financeChart');

    if (!ctx) {
        console.error("Element #financeChart tidak ditemukan!");
        return;
    }

    setTimeout(() => {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomeData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }, 500); // Delay 500ms agar elemen benar-benar ter-load
});
</script>
@endsection
