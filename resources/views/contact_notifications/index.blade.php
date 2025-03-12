@extends('layouts.app')

@section('title', 'Manajemen Penerima Notifikasi')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-blue-700 flex items-center mb-4">
        <i class="ni ni-bell-55 mr-2"></i> Manajemen Penerima Notifikasi
    </h2>

    <!-- Catatan Halaman -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini digunakan untuk mengatur daftar penerima notifikasi yang akan menerima rekapan kas per bulan melalui WhatsApp.
    </div>

    <!-- Tombol Tambah Penerima -->
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md mb-3" onclick="toggleModal('addModal')">
        <i class="ni ni-fat-add mr-2"></i> Tambah Penerima
    </button>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4">
            <h5 class="text-lg font-semibold text-blue-700">Daftar Penerima Notifikasi</h5>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm text-left">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Nomor Telepon</th>
                            <th class="p-3">Notifikasi</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-3">{{ $contact->nama }}</td>
                            <td class="p-3">{{ $contact->no_telepon }}</td>
                            <td class="p-3">
                                <form action="{{ route('contact_notifications.toggle', $contact->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1 rounded-md text-white {{ $contact->menerima_notif ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-500 hover:bg-gray-600' }}">
                                        <i class="ni {{ $contact->menerima_notif ? 'ni-bell-55' : 'ni-fat-remove' }}"></i>
                                        {{ $contact->menerima_notif ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-3 flex gap-2">
                                <!-- Tombol Edit -->
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md"
                                        onclick="editContact('{{ $contact->id }}', '{{ $contact->nama }}', '{{ $contact->no_telepon }}')">
                                    <i class="ni ni-ruler-pencil"></i> Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('contact_notifications.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md">
                                        <i class="ni ni-fat-remove"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">Belum ada penerima notifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg">
        <h5 class="text-lg font-semibold">Tambah Penerima Notifikasi</h5>
        <form action="{{ route('contact_notifications.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block">Nama</label>
                <input type="text" name="nama" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Nomor Telepon</label>
                <input type="text" name="no_telepon" class="w-full p-2 border rounded-md" required>
                <small class="text-gray-500">Nomor telepon harus diawali dengan <strong>62</strong>, bukan 08.</small>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="menerima_notif" id="notifCheckbox" class="mr-2" checked>
                <label for="notifCheckbox">Terima Notifikasi?</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mt-3 rounded-md">Tambah</button>
        </form>
        <button class="w-full bg-gray-500 text-white px-4 py-2 mt-3 rounded-md" onclick="toggleModal('addModal')">Batal</button>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-1/3 p-6 rounded-lg">
        <h5 class="text-lg font-semibold">Edit Penerima Notifikasi</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block">Nama</label>
                <input type="text" name="nama" id="editNama" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block">Nomor Telepon</label>
                <input type="text" name="no_telepon" id="editNoTelepon" class="w-full p-2 border rounded-md" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mt-3 rounded-md">Simpan Perubahan</button>
        </form>
        <button class="w-full bg-gray-500 text-white px-4 py-2 mt-3 rounded-md" onclick="toggleModal('editModal')">Batal</button>
    </div>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.tailwindcss.com/"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.tailwindcss.js"></script>
<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function editContact(id, nama, noTelepon) {
        document.getElementById('editNama').value = nama;
        document.getElementById('editNoTelepon').value = noTelepon;
        document.getElementById('editForm').action = `/contact_notifications/${id}`;
        toggleModal('editModal');
    }
</script>

@endsection
