@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $totalIncome = $incomes->sum('amount');
            $totalExpense = $expenses->sum('amount');
            $totalBalance = $totalIncome - $totalExpense;
        @endphp

        @foreach([
            ['title' => 'Total Pemasukan', 'amount' => $totalIncome, 'color' => 'bg-green-500'],
            ['title' => 'Total Saldo', 'amount' => $totalBalance, 'color' => 'bg-blue-500']
        ] as $card)
            <div class="{{ $card['color'] }} shadow-xl text-white p-6 rounded-lg text-center">
                <h5 class="text-lg font-bold">{{ $card['title'] }}</h5>
                <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($card['amount'], 0, ',', '.') }}</h2>
            </div>
        @endforeach
    </div>

    <!-- Tombol Tambah Pendapatan -->
    <button onclick="toggleModal('addIncomeModal')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md mt-4">
        + Tambah Pemasukan
    </button>

    <!-- Tabel Pendapatan -->
    <div class="mt-4">
        <div class="bg-white shadow-md rounded-lg p-6 overflow-x-auto">
            <table id="incomeTable" class="w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Kategori</th>
                        <th class="py-3 px-6 text-left">Jumlah</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">Deskripsi</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incomes as $income)
                        <tr class="border-b">
                            <td class="py-3 px-6">{{ $income->name }}</td>
                            <td class="py-3 px-6">{{ $income->category->name ?? '-' }}</td>
                            <td class="py-3 px-6">Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">{{ date('d M Y', strtotime($income->transaction_date)) }}</td>
                            <td class="py-3 px-6">{{ $income->description ?? '-' }}</td>
                            <td class="py-3 px-6 text-center">
                                <button onclick='editIncome(@json($income))' class="bg-yellow-500 text-white px-2 py-1 rounded-md">Edit</button>
                                <button onclick="deleteIncome({{ $income->id }})" class="bg-red-500 text-white px-2 py-1 rounded-md">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah & Edit Income -->
@include('finance.admin-rt.partials.income-modal', ['modalId' => 'addIncomeModal', 'action' => route('incomes.store'), 'method' => 'POST'])
@include('finance.admin-rt.partials.income-modal', ['modalId' => 'editIncomeModal', 'action' => '', 'method' => 'PUT'])

@push('scripts')

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById("incomeTable")) {
        new DataTable("#incomeTable");
    }

    window.toggleModal = function(modalId) {
        document.getElementById(modalId).classList.toggle("hidden");
    }

    window.editIncome = function(income) {
        const editForm = document.querySelector("#editIncomeModal form");
        if (!editForm) return console.error("Form Edit tidak ditemukan!");

        editForm.setAttribute("action", `/admin-rt/incomes/${income.id}`);
        editForm.querySelector("input[name='name']").value = income.name;
        editForm.querySelector("select[name='category_id']").value = income.category_id || "";
        editForm.querySelector("input[name='amount']").value = income.amount;
        editForm.querySelector("input[name='transaction_date']").value = income.transaction_date;
        editForm.querySelector("textarea[name='description']").value = income.description || "";

        toggleModal("editIncomeModal");
    }

    window.deleteIncome = function (id) {
        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Data yang dihapus akan berada dalam sampah",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin-rt/incomes/${id}`, {
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
                        showAlert("success", "Pemasukan berhasil dihapus!");
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert("error", "Gagal menghapus pemasukan.");
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
