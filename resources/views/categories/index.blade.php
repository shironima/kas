@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-blue-700 flex items-center mb-4">
        <i class="ni ni-tag mr-2"></i> Manajemen Kategori
    </h2>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengelola kategori artikel atau produk.
    </div>

    <!-- Tombol Tambah Kategori -->
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md mb-3" onclick="toggleModal('addModal')">
        <i class="ni ni-fat-add mr-2"></i>+ Tambah Kategori
    </button>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <table id="categoryTable" class="stripe hover w-full text-sm text-left">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td class="p-3">{{ $category->name }}</td>
                    <td class="p-3">{{ $category->description }}</td>
                    <td class="p-3 flex gap-2">
                        <!-- Tombol Edit -->
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md"
                                onclick="editCategory('{{ $category->id }}', '{{ $category->name }}', '{{ $category->description }}')">
                            <i class="ni ni-ruler-pencil"></i> Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md">
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

<!-- Modal Tambah -->
<div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-white bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg">
        <h5 class="text-lg font-semibold">Tambah Kategori</h5>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block">Nama Kategori</label>
                <input type="text" name="name" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Deskripsi</label>
                <textarea name="description" class="w-full p-2 border rounded-md" required></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mt-3 rounded-md">Tambah</button>
        </form>
        <button class="w-full bg-gray-500 text-white px-4 py-2 mt-3 rounded-md" onclick="toggleModal('addModal')">Batal</button>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg">
        <h5 class="text-lg font-semibold">Edit Kategori</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block">Nama Kategori</label>
                <input type="text" name="name" id="editName" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Deskripsi</label>
                <textarea name="description" id="editDescription" class="w-full p-2 border rounded-md" required></textarea>
            </div>
            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 mt-3 rounded-md">Simpan Perubahan</button>
        </form>
        <button class="w-full bg-gray-500 text-white px-4 py-2 mt-3 rounded-md" onclick="toggleModal('editModal')">Batal</button>
    </div>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.tailwindcss.com/"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>

<script>
    $(document).ready(function () {
        $('#categoryTable').DataTable();
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
</script>

@endsection
