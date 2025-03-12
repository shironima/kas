@extends('layouts.app')

@section('title', 'Dashboard - KAS RT')

@section('content')
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Statistik Keuangan -->
        <div class="bg-white shadow-md rounded-lg p-6 max-w-sm">
            <h5 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="bi bi-cash-stack text-blue-600 text-lg"></i> Statistik Keuangan
            </h5>
            <p class="text-gray-600 mt-2">Lihat ringkasan pemasukan dan pengeluaran kas RT.</p>
            <a href="#" class="block bg-blue-600 hover:bg-blue-700 text-white text-center py-2 mt-4 rounded-md transition">
                Lihat Detail
            </a>
        </div>

        <!-- Notifikasi -->
        <div class="bg-white shadow-md rounded-lg p-6 max-w-sm">
            <h5 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="bi bi-people-fill text-green-600 text-lg"></i> Notifikasi
            </h5>
            <p class="text-gray-600 mt-2">Kelola data kontak yang menerima notifikasi.</p>
            <a href="{{ route('contact_notifications.index') }}" class="block bg-green-600 hover:bg-green-700 text-white text-center py-2 mt-4 rounded-md transition">
                Kelola Notifikasi
            </a>
        </div>

        <!-- Laporan Kegiatan -->
        <div class="bg-white shadow-md rounded-lg p-6 max-w-sm">
            <h5 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="bi bi-calendar-event text-yellow-500 text-lg"></i> Laporan Kegiatan
            </h5>
            <p class="text-gray-600 mt-2">Pantau dan kelola kegiatan di lingkungan RT Anda.</p>
            <a href="#" class="block bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 mt-4 rounded-md transition">
                Lihat Laporan
            </a>
        </div>
    </div>
@endsection
