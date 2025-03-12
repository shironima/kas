@extends('layouts.app')

@section('title', 'Manajemen RT')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-blue-700 flex items-center mb-4">
        <i class="ni ni-tag mr-2"></i> Manajemen RT
    </h2>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengelola data RT dan ketuanya.
    </div>

    <!-- Tombol Tambah RT -->
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md mb-3" onclick="toggleModal('addModal')">
        <i class="ni ni-fat-add mr-2"></i>+ Tambah RT
    </button>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <table id="rtTable" class="stripe hover w-full text-sm text-left">
            <thead class="bg-gray-400 text-white">
                <tr>
                    <th class="p-3">Nama RT</th>
                    <th class="p-3">Ketua RT</th>
                    <th class="p-3">Kontak</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rts as $rt)
                <tr>
                    <td class="p-3">{{ $rt->name }}</td>
                    <td class="p-3">{{ $rt->head_name }}</td>
                    <td class="p-3">{{ $rt->head_contact }}</td>
                    <td class="p-3 flex gap-2">
                        <!-- Tombol Edit -->
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md"
                                onclick="editRT('{{ $rt->id }}', '{{ $rt->name }}', '{{ $rt->head_name }}', '{{ $rt->head_contact }}')">
                            <i class="ni ni-ruler-pencil"></i> Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('rt.destroy', $rt->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus RT ini?');">
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

<!-- Modal Tambah RT -->
<div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg">
        <h5 class="text-lg font-semibold">Tambah RT</h5>
        <form action="{{ route('rt.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block">Nama RT</label>
                <input type="text" name="name" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Nama Ketua RT</label>
                <input type="text" name="head_name" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Kontak Ketua RT</label>
                <input type="text" name="head_contact" class="w-full p-2 border rounded-md" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mt-3 rounded-md">Tambah</button>
        </form>
        <button class="w-full bg-gray-500 text-white px-4 py-2 mt-3 rounded-md" onclick="toggleModal('addModal')">Batal</button>
    </div>
</div>

<!-- Modal Edit RT -->
<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg">
        <h5 class="text-lg font-semibold">Edit RT</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block">Nama RT</label>
                <input type="text" name="name" id="editRTName" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Nama Ketua RT</label>
                <input type="text" name="head_name" id="editRTHead" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Kontak Ketua RT</label>
                <input type="text" name="head_contact" id="editRTContact" class="w-full p-2 border rounded-md" required>
            </div>
            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 mt-3 rounded-md">Simpan Perubahan</button>
        </form>
        <button class="w-full bg-gray-500 text-white px-4 py-2 mt-3 rounded-md" onclick="toggleModal('editModal')">Batal</button>
    </div>
</div>

<!-- DataTables -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>

<script>
    $(document).ready(function () {
        $('#rtTable').DataTable();
    });

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function editRT(id, name, head, contact) {
        document.getElementById('editRTName').value = name;
        document.getElementById('editRTHead').value = head;
        document.getElementById('editRTContact').value = contact;
        document.getElementById('editForm').action = '/rt/' + id;

        toggleModal('editModal');
    }
</script>
@endsection
