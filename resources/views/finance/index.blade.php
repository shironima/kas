@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $balanceClass = $totalBalance > 0 ? 'bg-green-500' : ($totalBalance == 0 ? 'bg-yellow-500' : 'bg-red-500');
        @endphp

        @foreach([
            ['title' => 'Total Pemasukan', 'amount' => $totalIncome ?? 0, 'color' => 'bg-green-500'],
            ['title' => 'Total Pengeluaran', 'amount' => $totalExpense ?? 0, 'color' => 'bg-red-500'],
            ['title' => 'Total Saldo', 'amount' => $totalBalance ?? 0, 'color' => $balanceClass]
        ] as $card)
            <div class="{{ $card['color'] }} shadow-lg text-white p-6 rounded-lg flex flex-col items-center">
                <h5 class="text-lg font-bold">{{ $card['title'] }}</h5>
                <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($card['amount'], 0, ',', '.') }}</h2>
            </div>
        @endforeach
    </div>

    <!-- Filter -->
    <form method="GET" class="my-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        @if(auth()->user()->role === 'super_admin')
            <select name="rt_id" class="border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300">
                <option value="">Pilih RT</option>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ request('rt_id') == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="category_id" class="border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-300">
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition w-full">
            Filter
        </button>
    </form>

    <!-- Tabs -->
    <div class="mb-6 flex justify-center border-b">
        @foreach(['income' => 'Pemasukan', 'expense' => 'Pengeluaran'] as $key => $label)
            <a href="{{ route('finance.index', ['tab' => $key] + request()->query()) }}" 
               class="py-3 px-6 text-lg font-semibold transition-all rounded-t-md 
                      {{ $tab == $key ? 'text-blue-600 border-b-4 border-blue-600 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Tabel Pemasukan/Pengeluaran -->
    <div class="mt-4 w-full">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="overflow-auto">
                @php
                    $role = auth()->user()->role;
                    $partialView = $role === 'super_admin' ? 'finance.super-admin.partials' : 'finance.admin-rt.partials';
                @endphp

                @if ($tab == 'income')
                    @include("{$partialView}.income-table", ['incomes' => $incomes])
                @else
                    @include("{$partialView}.expense-table", ['expenses' => $expenses])
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#incomeTable, #expenseTable').DataTable({
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
@endpush

@endsection
