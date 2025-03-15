@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Manajemen Admin RT</h2>

    <a href="{{ route('admin-rt.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
        + Tambah Admin RT
    </a>

    <table class="table-auto w-full mt-4 border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-300">
                <th class="border p-2">#</th>
                <th class="border p-2">Nama</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">RT</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $index => $admin)
                <tr class="border">
                    <td class="border p-2">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $admin->name }}</td>
                    <td class="border p-2">{{ $admin->email }}</td>
                    <td class="border p-2">{{ $admin->rt->name }}</td>
                    <td class="border p-2">
                        <a href="{{ route('admin-rt.edit', $admin->id) }}" class="text-yellow-500">Edit</a>
                        <form action="{{ route('admin-rt.destroy', $admin->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500" onclick="return confirm('Hapus akun ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
