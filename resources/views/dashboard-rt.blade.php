@extends('layouts.app')

@section('title', 'Dashboard Keuangan RT')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard Keuangan {{ $rtName }}</h2>

    <!-- KPI Keuangan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-kpi-card icon="bi-bank" title="Saldo Kas" value="Rp {{ number_format($saldoKas, 0, ',', '.') }}" />
        <x-kpi-card icon="bi-cash-stack" title="Total Pemasukan" value="Rp {{ number_format($totalIncome, 0, ',', '.') }}" />
        <x-kpi-card icon="bi-wallet2" title="Total Pengeluaran" value="Rp {{ number_format($totalExpense, 0, ',', '.') }}" />
    </div>

    <!-- Grafik Bar Chart + Tombol Input Data -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Bar Chart -->
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col justify-center items-center">
            <h5 class="font-semibold text-gray-800 mb-4 text-center">Grafik Keuangan Bulanan</h5>
            <div class="w-full max-w-md">
                <canvas id="financeBarChart"></canvas>
            </div>
        </div>

        <!-- Tombol Tambah Data -->
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col justify-center space-y-4">
            <a href="{{ route('incomes.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 text-center rounded-md">
                + Tambah Pemasukan
            </a>
            <a href="{{ route('expenses.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 text-center rounded-md">
                + Tambah Pengeluaran
            </a>
        </div>
    </div>

    <!-- Notifikasi Keuangan -->
    @if($saldoKas < 100000)
        <div class="bg-red-200 text-red-800 p-4 rounded-md mt-6">
            <strong>⚠️ Saldo kas menipis!</strong> Pertimbangkan untuk mengurangi pengeluaran atau mencari sumber pemasukan tambahan.
        </div>
    @endif

    <!-- Daftar Transaksi Terbaru -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
        <h5 class="font-semibold text-gray-800 mb-4">Transaksi Terbaru</h5>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Jenis</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Keterangan</th>
                        <th class="p-3 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr class="border-b">
                            <td class="p-3">{{ date('d M Y', strtotime($transaction->date)) }}</td>
                            <td class="p-3">{{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                            <td class="p-3">{{ $transaction->name }}</td>
                            <td class="p-3">{{ $transaction->description }}</td>
                            <td class="p-3 text-right {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('financeBarChart');
    if (!ctx) {
        console.error("Element #financeBarChart tidak ditemukan!");
        return;
    }

    const chartLabels = {!! json_encode($months) !!};
    const chartIncome = {!! json_encode($chartIncome) !!};
    const chartExpense = {!! json_encode($chartExpense) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: chartIncome,
                    backgroundColor: '#4CAF50'
                },
                {
                    label: 'Pengeluaran',
                    data: chartExpense,
                    backgroundColor: '#F44336'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush