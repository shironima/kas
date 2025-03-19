<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RT;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RTController extends Controller
{
    public function index() {
        $rts = RT::with('adminRT')->orderBy('name')->get();
        return view('rt.index', compact('rts'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:rts,name',
            'head_name' => 'required',
            'head_contact' => 'required',
        ]);

        RT::create($request->only(['name', 'head_name', 'head_contact']));
    
        return redirect()->route('rt.index')->with('success', 'RT berhasil ditambahkan.');
    }    

    public function update(Request $request, RT $rt) {
        $request->validate([
            'name' => 'required|unique:rts,name,' . $rt->id,
            'head_name' => 'required',
            'head_contact' => 'required',
            'email' => 'required|email|unique:users,email,' . optional($rt->adminRT)->id,
        ]);

        $rt->update($request->only(['name', 'head_name', 'head_contact']));

        // Update data admin_rt jika ada
        if ($rt->adminRT) {
            $rt->adminRT->update([
                'name' => $request->head_name,
                'email' => $request->email,
            ]);

            // Jika password diisi, update juga
            if ($request->filled('password')) {
                $rt->adminRT->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        return redirect()->route('rt.index')->with('success', 'RT successfully updated.');
    }

    public function destroy(RT $rt) {
        // Hapus admin_rt terkait jika ada
        if ($rt->adminRT) {
            $rt->adminRT->delete();
        }

        // Hapus RT
        $rt->delete();

        return redirect()->route('rt.index')->with('success', 'RT successfully deleted.');
    }
}
