<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RT;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminRTController extends Controller
{
    public function index()
    {
        $adminRT = User::where('role', 'admin_rt')->with('rt')->get();
        $rts = RT::all();

        return view('account.admin_rt.index', compact('adminRT', 'rts'));
    }

    public function create()
    {
        $rts = RT::all();
        return view('account.admin_rt.create', compact('rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rts_id' => 'required|exists:rts,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rts_id' => $request->rts_id,
        ]);

        $user->assignRole('admin_rt'); // Otomatis beri role admin_rt

        return redirect()->route('admin-rt.index')->with('success', 'Akun Admin RT berhasil dibuat.');
    }

    public function edit(User $admin_rt)
    {
        $rts = RT::all();
        return view('account.admin_rt.edit', compact('admin_rt', 'rts'));
    }

    public function update(Request $request, User $admin_rt)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin_rt->id,
            'password' => 'nullable|string|min:8',
            'rts_id' => 'required|exists:rts,id'
        ]);

        $admin_rt->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rts_id' => $request->rts_id,
        ]);

        return redirect()->route('admin-rt.index')->with('success', 'Akun Admin RT berhasil diperbarui.');
    }

    public function destroy(User $admin_rt)
    {
        $admin_rt->delete();
        return redirect()->route('admin-rt.index')->with('success', 'Akun Admin RT berhasil dihapus.');
    }
}
