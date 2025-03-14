@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!--- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-black-700 flex items-center">
            <i class="ni ni-tag mr-2"></i> Laporan Keuangan
        </h2>
    </div>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Silahkan pilih laporan yang akan di print-out.
    </div>

    <!-- Form Filter -->
    <form action="{{ route('finance.report.generate') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pilih RT -->
            <div>
                <label for="rts_id" class="block font-semibold text-gray-700">Pilih RT:</label>
                <select name="rts_id" id="rts_id" class="w-full p-2 border rounded">
                    <option value="">Semua RT</option>
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}" {{ request('rts_id') == $rt->id ? 'selected' : '' }}>
                            {{ $rt->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Tanggal Mulai -->
            <div>
                <label for="start_date" class="block font-semibold text-gray-700">Tanggal Mulai:</label>
                <input type="date" name="start_date" id="start_date" class="w-full p-2 border rounded" value="{{ request('start_date') }}" required>
            </div>

            <!-- Pilih Tanggal Akhir -->
            <div>
                <label for="end_date" class="block font-semibold text-gray-700">Tanggal Akhir:</label>
                <input type="date" name="end_date" id="end_date" class="w-full p-2 border rounded" value="{{ request('end_date') }}" required>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                Tampilkan Laporan
            </button>

            <!-- Tombol Download PDF -->
            <a href="{{ route('finance.report.pdf', request()->all()) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                Download PDF
            </a>
        </div>
    </form>

    <!-- Tampilkan Laporan -->
    @if(isset($incomes) && isset($expenses))
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Keuangan ({{ $periode }})</h3>

            @if(count($incomes) > 0 || count($expenses) > 0)
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="border border-gray-300 p-3">Tanggal</th>
                            <th class="border border-gray-300 p-3">Jenis</th>
                            <th class="border border-gray-300 p-3">Kategori</th>
                            <th class="border border-gray-300 p-3">RT</th>
                            <th class="border border-gray-300 p-3">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incomes as $income)
                        <tr class="text-green-600">
                            <td class="border border-gray-300 p-3">{{ $income->transaction_date }}</td>
                            <td class="border border-gray-300 p-3">Pemasukan</td>
                            <td class="border border-gray-300 p-3">{{ $income->category->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 p-3">{{ $income->rt->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 p-3 font-semibold">+Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach

                        @foreach($expenses as $expense)
                        <tr class="text-red-600">
                            <td class="border border-gray-300 p-3">{{ $expense->transaction_date }}</td>
                            <td class="border border-gray-300 p-3">Pengeluaran</td>
                            <td class="border border-gray-300 p-3">{{ $expense->category->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 p-3">{{ $expense->rt->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 p-3 font-semibold">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 font-bold text-gray-700">
                    <p>Total Pemasukan: <span class="text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
                    <p>Total Pengeluaran: <span class="text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span></p>
                    <p>Total Saldo: <span class="text-blue-600">Rp {{ number_format($totalBalance, 0, ',', '.') }}</span></p>
                </div>
            @else
                <p class="text-gray-500 text-lg text-center">Tidak ada data untuk RT yang dipilih.</p>
            @endif
        </div>
    @endif
</div>
@endsection
