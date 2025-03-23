@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Dashboard KAS RT</h2>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                function formatRupiahShort($value) {
                    if ($value >= 1000000000) {
                        return 'Rp ' . number_format($value / 1000000000, 1, ',', '.') . 'M';
                    } elseif ($value >= 1000000) {
                        return 'Rp ' . number_format($value / 1000000, 1, ',', '.') . 'jt';
                    } elseif ($value >= 1000) {
                        return 'Rp ' . number_format($value / 1000, 0, ',', '.') . 'rb';
                    } else {
                        return 'Rp ' . number_format($value, 0, ',', '.');
                    }
                }

                $balanceClass = $totalBalance > 0 ? 'from-green-400 to-green-600' : ($totalBalance == 0 ? 'from-yellow-400 to-yellow-600' : 'from-red-400 to-red-600');
            @endphp

            @foreach([
                ['title' => 'Total Saldo', 'amount' => $totalBalance, 'icon' => 'bi-wallet2', 'color' => $balanceClass],
                ['title' => 'Pemasukan Bulan Ini', 'amount' => $monthlyIncome, 'icon' => 'bi-arrow-down-circle', 'color' => 'from-green-400 to-green-600'],
                ['title' => 'Pengeluaran Bulan Ini', 'amount' => $monthlyExpense, 'icon' => 'bi-arrow-up-circle', 'color' => 'from-red-400 to-red-600'],
                ['title' => 'Jumlah RT Terdaftar', 'amount' => $totalRTs, 'icon' => 'bi-house-door', 'color' => 'from-yellow-400 to-yellow-600']
            ] as $card)
                <div class="p-6 rounded-xl shadow-lg transition transform hover:scale-105 bg-gradient-to-r {{ $card['color'] }} flex flex-col items-center text-white">
                    <div class="bg-white bg-opacity-25 p-4 rounded-full">
                        <i class="bi {{ $card['icon'] }} text-4xl"></i>
                    </div>
                    <h5 class="text-lg font-semibold mt-3">{{ $card['title'] }}</h5>
                    <h2 class="text-2xl font-bold mt-1">
                        {{ is_numeric($card['amount']) ? formatRupiahShort($card['amount']) : $card['amount'] }}
                    </h2>
                </div>
            @endforeach
        </div>

        <!-- Chart Section -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Statistik Pemasukan & Pengeluaran</h3>
            <div class="w-full h-96">
                <canvas id="financeChart"></canvas>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'Statistik Keuangan', 'desc' => 'Lihat ringkasan pemasukan dan pengeluaran kas RT.', 'route' => 'finance.index', 'icon' => 'bi-cash-stack', 'color' => 'blue'],
                ['title' => 'Notifikasi', 'desc' => 'Kelola data kontak yang menerima notifikasi.', 'route' => 'contact_notifications.index', 'icon' => 'bi-people-fill', 'color' => 'green'],
                ['title' => 'Laporan Kegiatan', 'desc' => 'Pantau dan kelola keuangan di lingkungan RT Anda.', 'route' => 'finance.report.generate', 'icon' => 'bi-calendar-event', 'color' => 'yellow']
            ] as $menu)
                <div class="bg-white shadow-md hover:shadow-lg transition p-6 rounded-xl flex flex-col items-center">
                    <div class="bg-{{ $menu['color'] }}-100 p-4 rounded-full">
                        <i class="bi {{ $menu['icon'] }} text-4xl text-{{ $menu['color'] }}-600"></i>
                    </div>
                    <h5 class="font-semibold text-gray-900 mt-4 text-lg">{{ $menu['title'] }}</h5>
                    <p class="text-gray-600 text-center text-sm">{{ $menu['desc'] }}</p>
                    <a href="{{ route($menu['route']) }}" class="bg-{{ $menu['color'] }}-600 hover:bg-{{ $menu['color'] }}-700 text-white px-5 py-2 mt-4 rounded-lg transition">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('financeChart').getContext('2d');

        var incomeData = {!! json_encode($incomeData) !!} || [];
        var expenseData = {!! json_encode($expenseData) !!} || [];
        var months = {!! json_encode($months) !!} || [];

        if (incomeData.length === 0 && expenseData.length === 0) {
            document.getElementById('financeChart').replaceWith(
                `<p class="text-center text-gray-500">Tidak ada data pemasukan atau pengeluaran.</p>`
            );
            return;
        }

        var financeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomeData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + (value >= 1000000 ? (value / 1000000).toFixed(1) + 'jt' : value.toLocaleString('id-ID'));
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    });
</script>
@endpush
