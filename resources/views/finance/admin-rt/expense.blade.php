@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $totalExpense = $expenses->sum('amount');
            $totalBalance = $totalIncome - $totalExpense;
        @endphp

        @foreach([
            ['title' => 'Total Pengeluaran', 'amount' => $totalExpense, 'color' => 'bg-red-400'],
            ['title' => 'Total Saldo', 'amount' => $totalBalance, 'color' => 'bg-blue-400']
        ] as $card)
            <div class="{{ $card['color'] }} shadow-xl text-white p-6 rounded-lg flex flex-col items-center">
                <h5 class="text-lg font-bold">{{ $card['title'] }}</h5>
                <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($card['amount'], 0, ',', '.') }}</h2>
            </div>
        @endforeach
    </div>

    <!-- Tombol Tambah Pengeluaran -->
    <button onclick="openModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-md mt-4">
        + Tambah Pengeluaran
    </button>

    <!-- Tabel Pengeluaran -->
    <div class="mt-4 w-full">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="overflow-x-auto">
                <table id="expenseTable" class="w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nama</th>
                            <th class="py-3 px-6 text-left">Kategori</th>
                            <th class="py-3 px-6 text-left">Jumlah</th>
                            <th class="py-3 px-6 text-left">Tanggal</th>
                            <th class="py-3 px-6 text-left">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td class="py-3 px-6">{{ $expense->name }}</td>
                                <td class="py-3 px-6">{{ $expense->category->name ?? '-' }}</td>
                                <td class="py-3 px-6">Rp{{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-6">{{ date('d M Y', strtotime($expense->transaction_date)) }}</td>
                                <td class="py-3 px-6">{{ $expense->description ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengeluaran -->
<div id="addExpenseModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="flex justify-between items-center border-b pb-2">
            <h5 class="text-lg font-semibold text-gray-700">Tambah Pengeluaran</h5>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <form id="addExpenseForm" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" class="w-full border p-2 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" class="w-full border p-2 rounded-md" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="amount" class="w-full border p-2 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="transaction_date" class="w-full border p-2 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" class="w-full border p-2 rounded-md"></textarea>
            </div>
            <div class="text-red-500 text-sm hidden" id="errorMessage"></div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>

<script>
$(document).ready(function() {
    $('#expenseTable').DataTable({
        "scrollX": false,
        "autoWidth": false,
        "responsive": true,
        "columns": [
            { "data": 0 }, // Nama
            { "data": 1 }, // Kategori
            { "data": 2 }, // Jumlah
            { "data": 3 }, // Tanggal
            { "data": 4 }  // Deskripsi
        ]
    });
});

// Fungsi untuk membuka modal tambah pengeluaran
function openModal() {
    document.getElementById('addExpenseModal').classList.remove('hidden');
}

// Fungsi untuk menutup modal tambah pengeluaran
function closeModal() {
    document.getElementById('addExpenseModal').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
}
</script>
@endpush
@endsection
