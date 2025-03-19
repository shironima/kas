<div class="bg-white shadow-md rounded-lg p-4 mt-6">
    <h3 class="text-lg font-semibold mb-4">Daftar Pemasukan</h3>

    <!-- Kolom Pencarian -->
    <div class="mb-4 flex justify-between">
        <input type="text" id="searchIncome" placeholder="Cari pemasukan..." class="p-2 border rounded-md w-1/3">
    </div>

    <!-- Tabel Pemasukan -->
    <div class="overflow-x-auto">
        <table id="incomeTable" class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border text-left">No</th>
                    <th class="px-4 py-2 border text-left">Tanggal</th>
                    <th class="px-4 py-2 border text-left">Nama</th>
                    <th class="px-4 py-2 border text-right">Jumlah</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($finances as $income)
                <tr class="text-gray-700 hover:bg-gray-100 transition">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($income->transaction_date)->format('d M Y') }}</td>
                    <td class="px-4 py-2 border">{{ $income->name }}</td>
                    <td class="px-4 py-2 border text-right">Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border text-center">
                        <button class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600" onclick="editIncome({{ $income->id }})">Edit</button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600" onclick="deleteIncome({{ $income->id }})">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
