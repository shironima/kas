<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RT;

class RTController extends Controller
{
    public function index() {
        $rts = RT::orderBy('name')->get();
        return view('rt.index', compact('rts'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:rts,name',
            'head_name' => 'required',
            'head_contact' => 'required'
        ]);

        RT::create($request->only(['name', 'head_name', 'head_contact']));

        return redirect()->route('rt.index')->with('success', 'RT successfully added.');
    }

    public function update(Request $request, RT $rt) {
        $request->validate([
            'name' => 'required|unique:rts,name,' . $rt->id,
            'head_name' => 'required',
            'head_contact' => 'required'
        ]);

        $rt->update($request->only(['name', 'head_name', 'head_contact']));

        return redirect()->route('rt.index')->with('success', 'RT successfully updated.');
    }

    public function destroy(RT $rt) {
        $rt->delete();

        return redirect()->route('rt.index')->with('success', 'RT successfully deleted.');
    }
}
