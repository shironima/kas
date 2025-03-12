<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h3 class="text-lg font-semibold mb-4">Daftar Pemasukan</h3>
    <table id="incomeTable" class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Tanggal</th>
                <th class="px-4 py-2 border">Sumber</th>
                <th class="px-4 py-2 border">Jumlah</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incomes as $key => $income)
            <tr class="text-center">
                <td class="px-4 py-2 border">{{ $key + 1 }}</td>
                <td class="px-4 py-2 border">{{ $income->transaction_date }}</td>
                <td class="px-4 py-2 border">{{ $income->source }}</td>
                <td class="px-4 py-2 border">Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                <td class="px-4 py-2 border">
                    <button class="btn btn-sm btn-warning" onclick="editIncome({{ $income->id }})">Edit</button>
                    <form action="{{ route('income.destroy', $income->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
