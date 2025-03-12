<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactNotification;

class ContactNotificationController extends Controller
{
    public function index()
    {
        $contacts = ContactNotification::all();
        return view('contact_notifications.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:20|unique:contact_notification,no_telepon',
        ]);

        ContactNotification::create([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'menerima_notif' => $request->has('menerima_notif') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Penerima notifikasi berhasil ditambahkan!');
    }

    // FUNGSI UNTUK UPDATE NAMA & NOMOR TELEPON
    public function update(Request $request, $id)
    {
        $contact = ContactNotification::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:20|unique:contact_notification,no_telepon,' . $id,
        ]);

        $contact->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->back()->with('success', 'Data penerima notifikasi berhasil diperbarui!');
    }

    // FUNGSI UNTUK TOGGLE NOTIFIKASI
    public function toggleNotification($id)
    {
        $contact = ContactNotification::findOrFail($id);
        $contact->update([
            'menerima_notif' => !$contact->menerima_notif,
        ]);

        return redirect()->back()->with('success', 'Status notifikasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        ContactNotification::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penerima notifikasi berhasil dihapus!');
    }
}
