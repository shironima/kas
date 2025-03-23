@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Sampah</h2>

    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-4">
        <i class="ni ni-single-copy-04"></i> <strong>Catatan:</strong> Halaman ini menyimpan data yang telah dihapus. Gunakan tombol 'Hapus Permanen' untuk benar benar menghapus data dari sistem.
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b">
        <button class="tab-button px-4 py-2 text-gray-600 border-b-2 border-transparent focus:outline-none" onclick="openTab('categories')">Kategori Terhapus</button>
        <button class="tab-button px-4 py-2 text-gray-600 border-b-2 border-transparent focus:outline-none" onclick="openTab('rts')">RT Terhapus</button>
        <button class="tab-button px-4 py-2 text-gray-600 border-b-2 border-transparent focus:outline-none" onclick="openTab('admin_rts')">Admin RT Terhapus</button>
    </div>

    <!-- Kategori Terhapus -->
    <div id="categories" class="tab-content bg-white p-6 shadow rounded-lg mt-4">
        <h3 class="text-lg font-semibold">Kategori yang Dihapus</h3>
        <table id="categoriesTable" class="w-full mt-2 border stripe hover">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashedCategories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <form action="{{ route('superadmin.trashbin.restore', ['type' => 'category', 'id' => $category->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Restore</button>
                        </form>
                        <form action="{{ route('superadmin.trashbin.forceDelete', ['type' => 'category', 'id' => $category->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Hapus permanen?')">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- RT Terhapus -->
    <div id="rts" class="tab-content bg-white p-6 shadow rounded-lg mt-4 hidden">
        <h3 class="text-lg font-semibold">RT yang Dihapus</h3>
        <table id="rtsTable" class="w-full mt-2 border stripe hover">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th>Nama RT</th>
                    <th>Ketua RT</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashedRTs as $rt)
                <tr>
                    <td>{{ $rt->name }}</td>
                    <td>{{ $rt->head_name }}</td>
                    <td>
                        <form action="{{ route('superadmin.trashbin.restore', ['type' => 'rt', 'id' => $rt->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Restore</button>
                        </form>
                        <form action="{{ route('superadmin.trashbin.forceDelete', ['type' => 'rt', 'id' => $rt->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Hapus permanen?')">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Admin RT Terhapus -->
    <div id="admin_rts" class="tab-content bg-white p-6 shadow rounded-lg mt-4 hidden">
        <h3 class="text-lg font-semibold">Admin RT yang Dihapus</h3>
        <table id="adminRTsTable" class="w-full mt-2 border stripe hover">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashedAdminRTs as $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <form action="{{ route('superadmin.trashbin.restore', ['type' => 'admin_rt', 'id' => $admin->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button class="bg-green-500 text-white px-2 py-1 rounded">Restore</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script>
    // Fungsi untuk mengaktifkan DataTables di tiap tabel
    $(document).ready(function () {
        $('#categoriesTable').DataTable();
        $('#rtsTable').DataTable();
        $('#adminRTsTable').DataTable();
    });

    // Fungsi untuk mengontrol tab
    function openTab(tabName) {
        $('.tab-content').addClass('hidden'); // Sembunyikan semua tab
        $('#' + tabName).removeClass('hidden'); // Tampilkan tab yang dipilih
        $('.tab-button').removeClass('border-blue-500 text-blue-500'); // Reset button active state
        event.currentTarget.classList.add('border-blue-500', 'text-blue-500'); // Highlight tab aktif
    }

    // Set default tab
    document.addEventListener("DOMContentLoaded", function () {
        openTab('categories');
    });
</script>
@endpush

@endsection
