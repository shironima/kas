@extends('layouts.app')

@section('title', 'Dashboard - KAS RT')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard KAS RT</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Statistik Keuangan -->
            <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="bi bi-cash-stack text-blue-600 text-2xl"></i>
                </div>
                <h5 class="font-semibold text-gray-800 mt-4">Statistik Keuangan</h5>
                <p class="text-gray-600 text-center mt-2 text-sm">Lihat ringkasan pemasukan dan pengeluaran kas RT.</p>
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mt-4 rounded-md transition">
                    Lihat Detail
                </a>
            </div>

            <!-- Notifikasi -->
            <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="bi bi-people-fill text-green-600 text-2xl"></i>
                </div>
                <h5 class="font-semibold text-gray-800 mt-4">Notifikasi</h5>
                <p class="text-gray-600 text-center mt-2 text-sm">Kelola data kontak yang menerima notifikasi.</p>
                <a href="{{ route('contact_notifications.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 mt-4 rounded-md transition">
                    Kelola Notifikasi
                </a>
            </div>

            <!-- Laporan Kegiatan -->
            <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="bi bi-calendar-event text-yellow-500 text-2xl"></i>
                </div>
                <h5 class="font-semibold text-gray-800 mt-4">Laporan Kegiatan</h5>
                <p class="text-gray-600 text-center mt-2 text-sm">Pantau dan kelola kegiatan di lingkungan RT Anda.</p>
                <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 mt-4 rounded-md transition">
                    Lihat Laporan
                </a>
            </div>
        </div>
    </div>
@endsection
