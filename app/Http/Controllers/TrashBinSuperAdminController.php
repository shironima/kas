<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RT;
use App\Models\User;
use App\Models\Category;    

class TrashBinSuperAdminController extends Controller
{
    // Menampilkan semua data yang telah dihapus (soft deleted)
    public function index()
    {
        $trashedRTs = RT::onlyTrashed()->get();
        $trashedAdminRTs = User::onlyTrashed()->get();
        $trashedCategories = Category::onlyTrashed()->get();

        return view('superadmin-trashbin', compact('trashedRTs', 'trashedAdminRTs', 'trashedCategories'));
    }

    // Restore data yang dihapus
    public function restore($type, $id)
    {
        if ($type == 'category') {
            $item = Category::onlyTrashed()->findOrFail($id);
        } elseif ($type == 'rt') {
            $item = RT::onlyTrashed()->findOrFail($id);
        } elseif ($type == 'admin_rt') {
            $item = User::onlyTrashed()->where('role', 'admin_rt')->findOrFail($id);
        } else {
            return redirect()->back()->with('error', 'Tipe tidak valid.');
        }

        $item->restore();
        return redirect()->back()->with('success', ucfirst($type) . ' berhasil dipulihkan.');
    }

    // Hapus permanen
    public function forceDelete($type, $id)
    {
        if ($type == 'category') {
            $item = Category::onlyTrashed()->findOrFail($id);
        } elseif ($type == 'rt') {
            $item = RT::onlyTrashed()->findOrFail($id);
        } elseif ($type == 'admin_rt') {
            $item = User::onlyTrashed()->where('role', 'admin_rt')->findOrFail($id);
        } else {
            return redirect()->back()->with('error', 'Tipe tidak valid.');
        }

        $item->forceDelete();
        return redirect()->back()->with('success', ucfirst($type) . ' berhasil dihapus permanen.');
    }
}
