<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'super-admin') abort(403);
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super-admin') abort(403);
        $request->validate(['name' => 'required|string|max:255']);
        
        Category::create(['name' => $request->name]);
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy(Category $category)
    {
        if (auth()->user()->role !== 'super-admin') abort(403);
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}