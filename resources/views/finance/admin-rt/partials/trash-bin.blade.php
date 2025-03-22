@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Trash Bin</h2>

    <!-- Tabs Navigation -->
    <div class="flex border-b">
        <button class="tab-button px-4 py-2 text-gray-600 border-b-2 border-transparent focus:outline-none" onclick="openTab('expenses')">Pengeluaran Terhapus</button>
        <button class="tab-button px-4 py-2 text-gray-600 border-b-2 border-transparent focus:outline-none" onclick="openTab('incomes')">Pemasukan Terhapus</button>
    </div>

    <!-- Pengeluaran Terhapus -->
    <div id="expenses" class="tab-content bg-white p-6 shadow rounded-lg mt-4">
        <h3 class="text-lg font-semibold">Pengeluaran Terhapus</h3>
        <table id="expensesTable" class="w-full mt-2 border stripe hover">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th class="py-2 px-4">Nama</th>
                    <th class="py-2 px-4">Jumlah</th>
                    <th class="py-2 px-4">Tanggal Transaksi</th>
                    <th class="py-2 px-4">Deskripsi</th>
                    <th class="py-2 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashedExpenses as $expense)
                <tr>
                    <td class="py-2 px-4">{{ $expense->name }}</td>
                    <td class="py-2 px-4">Rp{{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td class="py-2 px-4">{{ date('d M Y', strtotime($expense->transaction_date)) }}</td>
                    <td class="py-2 px-4">{{ $expense->description ?? '-' }}</td>
                    <td class="py-2 px-4">
                        <form action="{{ route('trash-bin.restore', ['type' => 'expense', 'id' => $expense->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Restore</button>
                        </form>
                        <form action="{{ route('trash-bin.forceDelete', ['type' => 'expense', 'id' => $expense->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Hapus permanen?')">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pemasukan Terhapus -->
    <div id="incomes" class="tab-content bg-white p-6 shadow rounded-lg mt-4 hidden">
        <h3 class="text-lg font-semibold">Pemasukan Terhapus</h3>
        <table id="incomesTable" class="w-full mt-2 border stripe hover">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th class="py-2 px-4">Nama</th>
                    <th class="py-2 px-4">Jumlah</th>
                    <th class="py-2 px-4">Tanggal Transaksi</th>
                    <th class="py-2 px-4">Deskripsi</th>
                    <th class="py-2 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashedIncomes as $income)
                <tr>
                    <td class="py-2 px-4">{{ $income->name }}</td>
                    <td class="py-2 px-4">Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                    <td class="py-2 px-4">{{ date('d M Y', strtotime($income->transaction_date)) }}</td>
                    <td class="py-2 px-4">{{ $income->description ?? '-' }}</td>
                    <td class="py-2 px-4">
                        <form action="{{ route('trash-bin.restore', ['type' => 'income', 'id' => $income->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Restore</button>
                        </form>
                        <form action="{{ route('trash-bin.forceDelete', ['type' => 'income', 'id' => $income->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Hapus permanen?')">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Tambahkan CDN DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Script untuk Tabs & DataTables -->
<script>
    function openTab(tabName) {
        // Sembunyikan semua tab
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Hapus style aktif dari semua tombol tab
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-blue-500', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-600');
        });

        // Tampilkan tab yang dipilih
        document.getElementById(tabName).classList.remove('hidden');

        // Tambahkan style aktif ke tombol yang ditekan
        event.currentTarget.classList.add('border-blue-500', 'text-blue-600');
    }

    // Set default tab
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.tab-button').click();
    });

    // Inisialisasi DataTables
    $(document).ready(function () {
        $('#expensesTable').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        $('#incomesTable').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>
@endsection
