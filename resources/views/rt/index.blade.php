@extends('layouts.app')

@section('title', 'Manajemen RT')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-blue-700 flex items-center">
            <i class="ni ni-building mr-2"></i> Manajemen RT
        </h2>
        <button onclick="toggleModal('addModal')" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
            <i class="ni ni-fat-add"></i> Tambah RT
        </button>
    </div>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengelola data RT dalam sistem.
    </div>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="overflow-x-auto">
            <table id="rtTable" class="stripe hover w-full text-sm text-left">
                <thead class="bg-gray-400 text-white">
                    <tr>
                        <th class="p-3 text-center">#</th>
                        <th class="p-3">Nama RT</th>
                        <th class="p-3">Ketua RT</th>
                        <th class="p-3">Kontak</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rts as $index => $rt)
                    <tr class="border-b">
                        <td class="p-3 text-center">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $rt->name }}</td>
                        <td class="p-3">{{ $rt->head_name }}</td>
                        <td class="p-3">{{ $rt->head_contact }}</td>
                        <td class="p-3 flex justify-center gap-2">
                            <!-- Tombol Edit -->
                            <button onclick="editRT('{{ $rt->id }}', '{{ $rt->name }}', '{{ $rt->head_name }}', '{{ $rt->head_contact }}')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md flex items-center gap-1">
                                <i class="ni ni-ruler-pencil"></i> Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('rt.destroy', $rt->id) }}" method="POST" onsubmit="return confirmDelete(event, this);">
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
        <h5 class="text-lg font-semibold mb-4">Tambah RT</h5>
        <form action="{{ route('rt.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block font-medium">Nama RT</label>
                <input type="text" name="name" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Nama Ketua RT</label>
                <input type="text" name="head_name" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Kontak Ketua RT</label>
                <input type="text" name="head_contact" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" onclick="toggleModal('addModal')">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
 
<script>
    $(document).ready(function () {
        $('#rtTable').DataTable({
            responsive: true,
            language: { search: "Cari RT:" }
        });
    });

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function editRT(id, name, head, contact) {
        document.getElementById('editRTName').value = name;
        document.getElementById('editRTHead').value = head;
        document.getElementById('editRTContact').value = contact;
        
        let form = document.getElementById('editForm');
        form.action = "{{ url('rt') }}/" + id;

        toggleModal('editModal');
    }

    function confirmDelete(event, form) {
        event.preventDefault();
        if (confirm('Yakin ingin menghapus RT ini?')) {
            form.submit();
        }
    }
</script>
@endsection
