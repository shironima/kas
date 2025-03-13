@extends('layouts.app')

@section('title', 'Manajemen Penerima Notifikasi')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-black-700 flex items-center">
            <i class="ni ni-bell-55 mr-2"></i> Manajemen Penerima Notifikasi
        </h2>
        <button onclick="toggleModal('addModal')" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
            <i class="ni ni-fat-add"></i> Tambah Penerima
        </button>
    </div>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengelola data penerima notifikasi dalam sistem.
    </div>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="overflow-x-auto">
            <table id="contactTable" class="stripe hover w-full text-sm text-left">
                <thead class="bg-gray-400 text-white">
                    <tr>
                        <th class="p-3 text-center">#</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Nomor Telepon</th>
                        <th class="p-3 text-center">Status Notifikasi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $index => $contact)
                    <tr class="border-b">
                        <td class="p-3 text-center">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $contact->nama }}</td>
                        <td class="p-3">{{ $contact->no_telepon }}</td>
                        <td class="p-3 text-center">
                            <form action="{{ route('contact_notifications.toggle', $contact->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="px-3 py-1 rounded-md text-white {{ $contact->menerima_notif ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-500 hover:bg-gray-600' }}">
                                    <i class="ni {{ $contact->menerima_notif ? 'ni-bell-55' : 'ni-fat-remove' }}"></i>
                                    {{ $contact->menerima_notif ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="p-3 flex justify-center gap-2">
                            <!-- Tombol Edit -->
                            <button onclick="editContact('{{ $contact->id }}', '{{ $contact->nama }}', '{{ $contact->no_telepon }}')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md flex items-center gap-1">
                                <i class="ni ni-ruler-pencil"></i> Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('contact_notifications.destroy', $contact->id) }}" method="POST" onsubmit="return confirmDelete(event, this);">
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
        <h5 class="text-lg font-semibold mb-4">Tambah Penerima Notifikasi</h5>
        <form action="{{ route('contact_notifications.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block font-medium">Nama</label>
                <input type="text" name="nama" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Nomor Telepon</label>
                <input type="text" name="no_telepon" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
                <small class="text-gray-500">Gunakan format: <strong>62xxxxxxxxxx</strong></small>
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
        <h5 class="text-lg font-semibold mb-4">Edit Penerima Notifikasi</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block font-medium">Nama</label>
                <input type="text" name="nama" id="editNama" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Nomor Telepon</label>
                <input type="text" name="no_telepon" id="editNoTelepon" class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" onclick="toggleModal('editModal')">Batal</button>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>

<script>
    $(document).ready(function () {
        $('#contactTable').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_ halaman",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": { "next": "Berikutnya", "previous": "Sebelumnya" }
            }
        });
    });

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function editContact(id, nama, noTelepon) {
        document.getElementById('editNama').value = nama;
        document.getElementById('editNoTelepon').value = noTelepon;
        document.getElementById('editForm').action = `/contact_notifications/${id}`;
        toggleModal('editModal');
    }

    function confirmDelete(event, form) {
        if (!confirm('Yakin ingin menghapus data ini?')) {
            event.preventDefault();
        }
    }
</script>
@endpush
@endsection
