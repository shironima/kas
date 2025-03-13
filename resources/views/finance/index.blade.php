@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-400 text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <h5 class="text-lg font-bold">Total Pemasukan</h5>
            <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($totalIncome ?? 0, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-red-400 text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <h5 class="text-lg font-bold">Total Pengeluaran</h5>
            <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($totalExpense ?? 0, 0, ',', '.') }}</h2>
        </div>
        <div class="{{ $totalBalance > 0 ? 'bg-green-400' : ($totalBalance == 0 ? 'bg-yellow-400' : 'bg-red-400') }} text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <h5 class="text-lg font-bold">Total Saldo</h5>
            <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($totalBalance ?? 0, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- Filter (Hanya untuk Super Admin) -->
    <form method="GET" class="my-6 flex flex-wrap gap-3 items-center">
        @if(auth()->user()->role === 'super_admin')
            <select name="rt_id" class="form-select border rounded-md p-2">
                <option value="">Pilih RT</option>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ $selectedRT == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="category_id" class="form-select border rounded-md p-2">
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            Filter
        </button>
    </form>

    <!-- Tabs untuk Pemasukan & Pengeluaran -->
    <div class="mb-6 border-b">
        <ul class="flex justify-center">
            <li class="mr-3">
                <a href="{{ route('finance.index', ['tab' => 'income', 'rt_id' => $selectedRT, 'category_id' => $selectedCategory]) }}" 
                   class="inline-block py-3 px-6 rounded-t-md text-lg font-semibold transition {{ $tab == 'income' ? 'text-blue-600 border-b-4 border-blue-600' : 'text-gray-500' }}">
                    Pemasukan
                </a>
            </li>
            <li class="mr-3">
                <a href="{{ route('finance.index', ['tab' => 'expense', 'rt_id' => $selectedRT, 'category_id' => $selectedCategory]) }}" 
                   class="inline-block py-3 px-6 rounded-t-md text-lg font-semibold transition {{ $tab == 'expense' ? 'text-red-600 border-b-4 border-red-600' : 'text-gray-500' }}">
                    Pengeluaran
                </a>
            </li>
        </ul>
    </div>

    <!-- Menampilkan Tabel Berdasarkan Role -->
    <div class="mt-4 w-full">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="overflow-x-auto">
                @if ($tab == 'income')
                    @if(auth()->user()->role === 'super_admin')
                        @include('finance.super-admin.partials.income-table', ['incomes' => $incomes])
                    @else
                        @include('finance.admin-rt.partials.income-table', ['incomes' => $incomes])
                    @endif
                @else
                    @if(auth()->user()->role === 'super_admin')
                        @include('finance.super-admin.partials.expense-table', ['expenses' => $expenses])
                    @else
                        @include('finance.admin-rt.partials.expense-table', ['expenses' => $expenses])
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/edit-income.js') }}"></script>
<script src="{{ asset('js/edit-expense.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>
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
