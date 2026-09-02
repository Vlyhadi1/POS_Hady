<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['user'])->withCount('produk')
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:categories,nama',
            'icon' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['warna'] = $request->warna ?: '#22c55e';
        $data['user_id'] = auth()->id();

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:categories,nama,' . $category->id,
            'icon' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['warna'] = $request->warna ?: '#22c55e';

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->produk()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak bisa dihapus karena masih digunakan oleh produk.'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'Kategori berhasil dihapus.'
        );
    }
}