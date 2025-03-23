<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
        ]);

        Category::create($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category) {
        return response()->json($category);
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable',
        ]);

        $category->update($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
