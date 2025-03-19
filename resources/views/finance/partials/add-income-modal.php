<!-- Modal Tambah Pemasukan -->
<div id="addIncomeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="flex justify-between items-center border-b pb-2">
            <h5 class="text-lg font-semibold text-gray-700">Tambah Pemasukan</h5>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <form id="addIncomeForm">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" class="w-full border p-2 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" class="w-full border p-2 rounded-md">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="amount" class="w-full border p-2 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" class="w-full border p-2 rounded-md"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="transaction_date" class="w-full border p-2 rounded-md" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
