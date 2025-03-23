<div class="bg-white shadow-md rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Daftar Pengeluaran</h3>
    <div class="overflow-x-auto rounded-lg">
        <table id="expenseTable" class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 text-black uppercase text-sm">
                    <th class="px-4 py-3 border">No</th>
                    <th class="px-4 py-3 border">Tanggal</th>
                    <th class="px-4 py-3 border">Kategori</th>
                    <th class="px-4 py-3 border">Deskripsi</th>
                    <th class="px-4 py-3 border">Jumlah</th>
                    <th class="px-4 py-3 border">RT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $key => $expense)
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                    <td class="px-4 py-3 border">{{ $key + 1 }}</td>
                    <td class="px-4 py-3 border">{{ $expense->transaction_date }}</td>
                    <td class="px-4 py-3 border">{{ $expense->category->name }}</td>
                    <td class="px-4 py-3 border">{{ $expense->description }}</td>
                    <td class="px-4 py-3 border text-nowrap font-semibold text-gray-700">Rp{{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 border">{{ $expense->rt->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
