@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
            <h5 class="text-lg font-bold">Total Pemasukan</h5>
            <h2 class="text-2xl">Rp{{ number_format($totalIncome ?? 0, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-red-500 text-white p-6 rounded-lg shadow-lg">
            <h5 class="text-lg font-bold">Total Pengeluaran</h5>
            <h2 class="text-2xl">Rp{{ number_format($totalExpense ?? 0, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- Filter (Hanya untuk Super Admin) -->
    <form method="GET" class="my-4 flex flex-wrap gap-2">
        @if(auth()->user()->role === 'super_admin')
            <select name="rt_id" class="form-select">
                <option value="">Pilih RT</option>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ $selectedRT == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="category_id" class="form-select">
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabs untuk Pemasukan & Pengeluaran -->
    <ul class="flex border-b">
        <li class="mr-1">
            <a href="{{ route('finance.index', ['tab' => 'income', 'rt_id' => $selectedRT, 'category_id' => $selectedCategory]) }}" 
               class="inline-block py-2 px-4 {{ $tab == 'income' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500' }}">
                Pemasukan
            </a>
        </li>
        <li class="mr-1">
            <a href="{{ route('finance.index', ['tab' => 'expense', 'rt_id' => $selectedRT, 'category_id' => $selectedCategory]) }}" 
               class="inline-block py-2 px-4 {{ $tab == 'expense' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-500' }}">
                Pengeluaran
            </a>
        </li>
    </ul>

    <!-- Menampilkan Tabel Berdasarkan Role -->
    <div class="mt-4 w-full">
        @if ($tab == 'income')
            @if(auth()->user()->role === 'super_admin')
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="overflow-x-auto">
                        @include('finance.super-admin.partials.income-table', ['incomes' => $incomes])
                    </div>
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="overflow-x-auto">
                        @include('finance.admin-rt.partials.income-table', ['incomes' => $incomes])
                    </div>
                </div>
            @endif
        @else
            @if(auth()->user()->role === 'super_admin')
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="overflow-x-auto">
                        @include('finance.super-admin.partials.expense-table', ['expenses' => $expenses])
                    </div>
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="overflow-x-auto">
                        @include('finance.admin-rt.partials.expense-table', ['expenses' => $expenses])
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/edit-income.js') }}"></script>
<script src="{{ asset('js/edit-expense.js') }}"></script>
<script>
$(document).ready(function() {
    $('#incomeTable, #expenseTable').DataTable({
        "scrollX": false, 
        "autoWidth": false, 
        "responsive": true 
    });
});
</script>
@endpush
@endsection
