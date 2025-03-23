@extends('layouts.app')

@section('title', 'Manajemen Admin RT')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-black-700 flex items-center">
            <i class="ni ni-single-02 mr-2"></i> Manajemen Admin RT
        </h2>
        <button onclick="toggleModal('addModal')" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
            <i class="ni ni-fat-add"></i> Tambah Admin RT
        </button>
    </div>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengelola akun admin RT.
    </div>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="overflow-x-auto">
            <table id="adminRtTable" class="stripe hover w-full text-sm text-left">
                <thead class="bg-gray-400 text-white">
                    <tr>
                        <th class="p-3 text-center">#</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">RT</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adminRT as $index => $admin)
                    <tr class="border-b">
                        <td class="p-3 text-center">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $admin->name }}</td>
                        <td class="p-3">{{ $admin->email }}</td>
                        <td class="p-3">{{ $admin->rt->name ?? '-' }}</td>
                        <td class="p-3 flex justify-center gap-2">
                            <!-- Tombol Edit -->
                            <button onclick="editAdminRT(this)" 
                                    data-id="{{ $admin->id }}" 
                                    data-name="{{ $admin->name }}" 
                                    data-email="{{ $admin->email }}" 
                                    data-rt="{{ $admin->rts_id }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md flex items-center gap-1">
                                <i class="ni ni-ruler-pencil"></i> Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin-rt.destroy', $admin->id) }}" method="POST" onsubmit="return confirmDelete(event, this);">
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
        <h5 class="text-lg font-semibold mb-2">Tambah Admin RT</h5>
        <p class="text-sm text-gray-700 mb-4">Email dan password yang dimasukkan akan digunakan untuk login ke sistem.</p>
        <form action="{{ route('admin-rt.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block font-medium">Nama</label>
                <input type="text" name="name" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">RT</label>
                <select name="rts_id" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
                    <option value="">-- Pilih RT --</option>
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                    @endforeach
                </select>
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
        <h5 class="text-lg font-semibold mb-4">Edit Admin RT</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId" name="id">
            
            <div class="mb-3">
                <label class="block font-medium">Nama</label>
                <input type="text" id="editName" name="name" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Email</label>
                <input type="email" id="editEmail" name="email" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Password Baru (Opsional)</label>
                <input type="password" id="editPassword" name="password" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200">
                <p class="text-xs text-gray-600 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
            </div>
            <div class="mb-3">
                <label class="block font-medium">RT</label>
                <select id="editRT" name="rts_id" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
                    <option value="">-- Pilih RT --</option>
                    @foreach ($rts as $rt)
                        <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" onclick="toggleModal('editModal')">Batal</button>
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#adminRtTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { first: "Pertama", last: "Terakhir", next: "›", previous: "‹" },
                zeroRecords: "Tidak ada data yang ditemukan",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)"
            }
        });
    });

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function addAdminRT() {
        toggleModal('addModal');
    }

    function editAdminRT(button) {
        let id = button.getAttribute('data-id');
        let name = button.getAttribute('data-name');
        let email = button.getAttribute('data-email');
        let rtId = button.getAttribute('data-rt');

        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRT').value = rtId;

        document.getElementById('editForm').action = "{{ route('admin-rt.update', '') }}" + '/' + id;

        toggleModal('editModal');
    }

    function confirmDelete(event, form) {
        event.preventDefault();
        if (confirm('Yakin ingin menghapus akun Admin RT ini?')) {
            form.submit();
        }
    }
</script>

@endpush
@endsection
