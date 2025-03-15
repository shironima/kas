@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $balanceClass = $totalBalance > 0 ? 'bg-green-400' : ($totalBalance == 0 ? 'bg-yellow-400' : 'bg-red-400');
        @endphp

        @foreach([
            ['title' => 'Total Pemasukan', 'amount' => $totalIncome ?? 0, 'color' => 'bg-green-400'],
            ['title' => 'Total Pengeluaran', 'amount' => $totalExpense ?? 0, 'color' => 'bg-red-400'],
            ['title' => 'Total Saldo', 'amount' => $totalBalance ?? 0, 'color' => $balanceClass]
        ] as $card)
            <div class="{{ $card['color'] }} shadow-xl text-white p-6 rounded-lg flex flex-col items-center">
                <h5 class="text-lg font-bold">{{ $card['title'] }}</h5>
                <h2 class="text-3xl font-semibold mt-2">Rp{{ number_format($card['amount'], 0, ',', '.') }}</h2>
            </div>
        @endforeach
    </div>

    <!-- Filter -->
    <form method="GET" class="my-6 flex flex-wrap gap-3 items-center">
        @if(auth()->user()->role === 'super_admin')
            <select name="rt_id" class="form-select border rounded-md p-2">
                <option value="">Pilih RT</option>
                @foreach ($rts as $rt)
                    <option value="{{ $rt->id }}" {{ request('rt_id') == $rt->id ? 'selected' : '' }}>
                        {{ $rt->name }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="category_id" class="form-select border rounded-md p-2">
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            Filter
        </button>
    </form>

    <!-- Tabs -->
    <div class="mb-6 border-b">
        <ul class="flex justify-center">
            @foreach(['income' => 'Pemasukan', 'expense' => 'Pengeluaran'] as $key => $label)
                <li class="mr-3">
                    <a href="{{ route('finance.index', ['tab' => $key] + request()->query()) }}" 
                       class="inline-block py-3 px-6 rounded-t-md text-lg font-semibold transition 
                              {{ $tab == $key ? 'text-blue-600 border-b-4 border-blue-600' : 'text-gray-500' }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Tabel Pemasukan/Pengeluaran -->
    <div class="mt-4 w-full">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="overflow-x-auto">
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
<script src="{{ asset('js/edit-income.js') }}"></script>
<script src="{{ asset('js/edit-expense.js') }}"></script>

<!-- Load jQuery hanya sekali -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
