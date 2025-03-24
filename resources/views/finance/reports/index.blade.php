@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Laporan Keuangan</h2>
    </div>

    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <strong>Catatan:</strong> Silakan pilih laporan yang akan di-print atau di-download.
    </div>

    <form action="{{ route('report.data') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block font-semibold text-gray-700">Pilih RT:</label>
                <select name="rts_id" class="w-full p-2 border rounded">
                    <option value="">Semua RT</option>
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}" {{ request('rts_id') == $rt->id ? 'selected' : '' }}>
                            {{ $rt->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Tanggal Mulai:</label>
                <input type="date" name="start_date" class="w-full p-2 border rounded" value="{{ request('start_date') }}">
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Tanggal Akhir:</label>
                <input type="date" name="end_date" class="w-full p-2 border rounded" value="{{ request('end_date') }}">
            </div>
        </div>

        <div class="mt-6 flex flex-wrap justify-between">
            <!-- <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                Tampilkan Laporan
            </button> -->

            <div class="space-x-2">
                <a href="{{ route('report.export', request()->all()) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                    Download PDF
                </a>
                <!-- <a href="{{ route('report.rt_analysis', request()->all()) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition">
                    Download RT Analysis PDF
                </a> -->
            </div>
        </div>
    </form>

    @if(isset($incomes) && isset($expenses))
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Keuangan</h3>
            @if(count($incomes) > 0 || count($expenses) > 0)
                <p>Total Pemasukan: Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                <p>Total Pengeluaran: Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                <p>Total Saldo: Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
            @else
                <p class="text-gray-500 text-lg text-center">Tidak ada data untuk filter yang dipilih.</p>
            @endif
        </div>
    @endif
</div>
@endsection
