<div id="{{ $modalId }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-lg font-bold mb-4">{{ $method == 'POST' ? 'Tambah' : 'Edit' }} Pendapatan</h2>
        
        <form method="POST" action="{{ $action }}">
            @csrf
            @if ($method == 'PUT')
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="block">Nama</label>
                <input type="text" name="name" class="w-full border p-2 rounded-md">
            </div>

            <div class="mb-3">
                <label for="category_id" class="block">Kategori</label>
                <select name="category_id" class="w-full border p-2 rounded-md">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="amount" class="block">Jumlah</label>
                <input type="number" name="amount" class="w-full border p-2 rounded-md">
            </div>

            <div class="mb-3">
                <label for="transaction_date" class="block">Tanggal</label>
                <input type="date" name="transaction_date" class="w-full border p-2 rounded-md">
            </div>

            <div class="mb-3">
                <label for="description" class="block">Deskripsi</label>
                <textarea name="description" class="w-full border p-2 rounded-md"></textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="toggleModal('{{ $modalId }}')" class="bg-gray-500 text-white px-3 py-2 rounded-md">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>
