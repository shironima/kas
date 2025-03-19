@extends('layouts.app')

@section('title', 'Dashboard Admin RT')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard Keuangan RT</h2>

    <!-- Statistik Keuangan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="bi bi-cash-stack text-blue-600 text-2xl"></i>
            </div>
            <h5 class="font-semibold text-gray-800 mt-4">Total Pemasukan</h5>
            <p class="text-gray-600 text-center mt-2 text-lg font-bold">
                Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
            <div class="bg-red-100 p-3 rounded-full">
                <i class="bi bi-wallet2 text-red-600 text-2xl"></i>
            </div>
            <h5 class="font-semibold text-gray-800 mt-4">Total Pengeluaran</h5>
            <p class="text-gray-600 text-center mt-2 text-lg font-bold">
                Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Chart Keuangan -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
        <h5 class="font-semibold text-gray-800 mb-4">Grafik Pemasukan & Pengeluaran</h5>
        <canvas id="financeChart"></canvas>
    </div>

    <!-- Shortcut Input Data -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
        <a href="{{ route('incomes.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 text-center rounded-md">
            + Tambah Pemasukan
        </a>
        <a href="{{ route('expenses.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 text-center rounded-md">
            + Tambah Pengeluaran
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    const financeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
                {
                    label: 'Pemasukan',
                    data: {!! json_encode(array_values($monthlyIncome->toArray() ?? [])) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode(array_values($monthlyExpense->toArray() ?? [])) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
