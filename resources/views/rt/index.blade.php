@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4" x-data="{ open: false, openEdit: false, editRTId: null, editRTName: '', editRTHead: '', editRTContact: '' }">
    <h2 class="text-2xl font-bold mb-4 text-blue-600 flex items-center">
        <i class="ni ni-building mr-2"></i> RT Management
    </h2>

    <!-- Tombol Tambah RT -->
    <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded mb-3 flex items-center">
        <i class="ni ni-fat-add mr-2"></i> Tambah RT
    </button>

    <!-- Tabel Daftar RT -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4">
            <h5 class="text-lg font-semibold text-blue-600">Daftar RT</h5>
            <table class="min-w-full border-collapse border border-gray-200" id="rtTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">#</th>
                        <th class="border p-2">Nama RT</th>
                        <th class="border p-2">Ketua RT</th>
                        <th class="border p-2">Kontak</th>
                        <th class="border p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rts as $rt)
                    <tr class="border">
                        <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                        <td class="border p-2">{{ $rt->name }}</td>
                        <td class="border p-2">{{ $rt->head_name }}</td>
                        <td class="border p-2">{{ $rt->head_contact }}</td>
                        <td class="border p-2 text-center">
                            <!-- Tombol Edit -->
                            <button @click="editRTId = {{ $rt->id }}; editRTName = '{{ $rt->name }}'; editRTHead = '{{ $rt->head_name }}'; editRTContact = '{{ $rt->head_contact }}'; openEdit = true;"
                                class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">
                                <i class="ni ni-ruler-pencil"></i> Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('rt.destroy', $rt->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus RT ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm">
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

    <!-- Modal Tambah RT -->
    <div x-show="open" x-transition class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-5 rounded-lg w-1/3">
            <h5 class="text-lg font-bold text-blue-600">Tambah RT</h5>
            <form action="{{ route('rt.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block font-semibold">Nama RT</label>
                    <input type="text" name="name" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block font-semibold">Nama Ketua RT</label>
                    <input type="text" name="head_name" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block font-semibold">Kontak Ketua RT</label>
                    <input type="text" name="head_contact" class="w-full border p-2 rounded" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit RT -->
    <div x-show="openEdit" x-transition class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-5 rounded-lg w-1/3">
            <h5 class="text-lg font-bold text-yellow-600">Edit RT</h5>
            <form x-bind:action="'/rt/' + editRTId" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block font-semibold">Nama RT</label>
                    <input type="text" name="name" x-model="editRTName" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block font-semibold">Nama Ketua RT</label>
                    <input type="text" name="head_name" x-model="editRTHead" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block font-semibold">Kontak Ketua RT</label>
                    <input type="text" name="head_contact" x-model="editRTContact" class="w-full border p-2 rounded" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables & Alpine.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<script>
    $(document).ready(function () {
        $('#rtTable').DataTable();
    });
</script>
@endsection