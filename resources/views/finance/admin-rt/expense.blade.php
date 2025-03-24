@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $totalIncome = $incomes->sum('amount');
            $totalExpense = $expenses->sum('amount') ?? 0;
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
    <button onclick="toggleModal('addExpenseModal')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-md mt-4">
        + Tambah Pengeluaran
    </button>

    <!-- Tabel Pengeluaran Tanpa DataTables -->
    <div class="mt-4 w-full">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="overflow-x-auto">
                <table id="RTExpense" class="w-full bg-white border border-gray-200 text-sm rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                            <th class="py-3 px-6 text-left">Nama</th>
                            <th class="py-3 px-6 text-left">Kategori</th>
                            <th class="py-3 px-6 text-left">Jumlah</th>
                            <th class="py-3 px-6 text-left">Tanggal</th>
                            <th class="py-3 px-6 text-left">Deskripsi</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $expense->name }}</td>
                                <td class="py-3 px-6">{{ $expense->category->name ?? '-' }}</td>
                                <td class="py-3 px-6">Rp{{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-6">{{ date('d M Y', strtotime($expense->transaction_date)) }}</td>
                                <td class="py-3 px-6">{{ $expense->description ?? '-' }}</td>
                                <td class="py-3 px-6 text-center">
                                    <button onclick='editExpense(@json($expense))' class="bg-yellow-500 text-white px-2 py-1 rounded-md">Edit</button>
                                    <button onclick="deleteExpense({{ $expense->id }})" class="bg-red-500 text-white px-2 py-1 rounded-md">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengeluaran -->
@include('finance.admin-rt.partials.expense-modal', ['modalId' => 'addExpenseModal', 'action' => route('expenses.store'), 'method' => 'POST'])

<!-- Modal Edit Pengeluaran -->
@include('finance.admin-rt.partials.expense-modal', ['modalId' => 'editExpenseModal', 'action' => '', 'method' => 'PUT'])

@push('scripts')

<script>
    $(document).ready(function() {
        $('#RTExpense').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { first: "Pertama", last: "Terakhir", next: "›", previous: "‹" },
                zeroRecords: "Tidak ada data yang ditemukan",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)"
            }
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    window.toggleModal = function(modalId) {
        document.getElementById(modalId).classList.toggle("hidden");
    }

    window.editExpense = function(expense) {
        const editForm = document.querySelector("#editExpenseModal form");
        if (!editForm) {
            console.error("Form Edit tidak ditemukan!");
            return;
        }

        editForm.setAttribute("action", `/admin-rt/expenses/${expense.id}`);
        editForm.querySelector("input[name='name']").value = expense.name;
        editForm.querySelector("select[name='category_id']").value = expense.category_id || "";
        editForm.querySelector("input[name='amount']").value = expense.amount;
        editForm.querySelector("input[name='transaction_date']").value = expense.transaction_date;
        editForm.querySelector("textarea[name='description']").value = expense.description || "";
        
        toggleModal("editExpenseModal");
    }

    window.deleteExpense = function (id) {
        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Data yang dihapus akan berada dalam sampah!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin-rt/expenses/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ _token: document.querySelector('meta[name="csrf-token"]').content })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert("success", "Pengeluaran berhasil dihapus!");
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert("error", "Gagal menghapus pengeluaran.");
                    }
                })
                .catch(error => {
                    console.error("Kesalahan:", error);
                    showAlert("error", "Terjadi kesalahan pada server.");
                });
            }
        });
    }

    function showAlert(type, message) {
        Swal.fire({
            title: type === "success" ? "Berhasil!" : "Gagal!",
            text: message,
            icon: type,
            timer: 3000,
            showConfirmButton: false
        });
    }
});
</script>
@endpush

@endsection
