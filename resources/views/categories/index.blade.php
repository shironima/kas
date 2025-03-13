@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-black-700 flex items-center">
            <i class="ni ni-tag mr-2"></i> Manajemen Kategori
        </h2>
        <button onclick="toggleModal('addModal')" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
            <i class="ni ni-fat-add"></i> Tambah Kategori
        </button>
    </div>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengelola kategori pemasukan atau pengeluaran.
    </div>

    <!-- Filter -->
    <form method="GET" class="my-6 flex flex-wrap gap-3 items-center">
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

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="overflow-x-auto">
            <table id="categoryTable" class="stripe hover w-full text-sm text-left">
                <thead class="bg-gray-400 text-white">
                    <tr>
                        <th class="p-3 text-center">#</th>
                        <th class="p-3">Nama Kategori</th>
                        <th class="p-3">Deskripsi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $index => $category)
                    <tr class="border-b">
                        <td class="p-3 text-center">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $category->name }}</td>
                        <td class="p-3">{{ $category->description }}</td>
                        <td class="p-3 flex justify-center gap-2">
                            <!-- Tombol Edit -->
                            <button onclick="editCategory('{{ $category->id }}', '{{ $category->name }}', '{{ $category->description }}')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md flex items-center gap-1">
                                <i class="ni ni-ruler-pencil"></i> Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirmDelete(event, this);">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md flex items-center gap-1">
                                    <i class="ni ni-fat-remove"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg">
        <h5 class="text-lg font-semibold mb-4">Tambah Kategori</h5>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block font-medium">Nama Kategori</label>
                <input type="text" name="name" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Deskripsi</label>
                <textarea name="description" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" onclick="toggleModal('addModal')">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg">
        <h5 class="text-lg font-semibold mb-4">Edit Kategori</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block font-medium">Nama Kategori</label>
                <input type="text" name="name" id="editName" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Deskripsi</label>
                <textarea name="description" id="editDescription" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" onclick="toggleModal('editModal')">Batal</button>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>

<script>
$(document).ready(function() {
    $('#categoryTable').DataTable({
        "scrollX": false, 
        "autoWidth": false, 
        "responsive": true 
    });
});

function toggleModal(modalId) {
    document.getElementById(modalId).classList.toggle('hidden');
}

function editCategory(id, name, description) {
    document.getElementById('editForm').action = `/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    toggleModal('editModal');
}

function confirmDelete(event, form) {
    event.preventDefault();
    if (confirm("Yakin ingin menghapus kategori ini?")) {
        form.submit();
    }
}
</script>
@endpush

@endsection
