@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="ni ni-tag mr-2"></i> Manajemen Kategori
        </h2>
        <button @click="openAddModal"
            class="btn btn-primary">
            <i class="ni ni-fat-add"></i> Tambah Kategori
        </button>
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="table w-full">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Deskripsi</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach ($categories as $category)
                <tr>
                    <td class="py-2 px-4">{{ $category->name }}</td>
                    <td class="py-2 px-4">{{ $category->description }}</td>
                    <td class="py-2 px-4 text-center">
                        <button @click="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                            class="btn btn-warning btn-sm">
                            <i class="ni ni-ruler-pencil"></i> Edit
                        </button>

                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error btn-sm">
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

<!-- Alpine.js -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Modal Tambah & Edit -->
<div x-data="categoryModal()" x-cloak>
    <!-- Modal Tambah -->
    <div x-show="isAddModalOpen" class="modal modal-open">
        <div class="modal-box">
            <h2 class="text-xl font-semibold mb-4">Tambah Kategori</h2>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="label">Nama Kategori</label>
                    <input type="text" name="name" class="input input-bordered w-full">
                </div>
                <div class="mb-3">
                    <label class="label">Deskripsi</label>
                    <textarea name="description" class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" @click="isAddModalOpen = false" class="btn">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="isEditModalOpen" class="modal modal-open">
        <div class="modal-box">
            <h2 class="text-xl font-semibold mb-4">Edit Kategori</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="label">Nama Kategori</label>
                    <input type="text" name="name" x-model="editName" class="input input-bordered w-full">
                </div>
                <div class="mb-3">
                    <label class="label">Deskripsi</label>
                    <textarea name="description" x-model="editDescription" class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" @click="isEditModalOpen = false" class="btn">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function categoryModal() {
    return {
        isAddModalOpen: false,
        isEditModalOpen: false,
        editId: '',
        editName: '',
        editDescription: '',

        openAddModal() {
            this.isAddModalOpen = true;
        },

        openEditModal(id, name, description) {
            this.editId = id;
            this.editName = name;
            this.editDescription = description;
            document.getElementById('editForm').action = `/categories/${id}`;
            this.isEditModalOpen = true;
        }
    };
}
</script>

@endsection
