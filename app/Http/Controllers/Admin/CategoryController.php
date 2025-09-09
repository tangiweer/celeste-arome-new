<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(12);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:categories,name'],
            'slug' => ['nullable','string','max:120','unique:categories,slug'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success','Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:categories,name,'.$category->id],
            'slug' => ['nullable','string','max:120','unique:categories,slug,'.$category->id],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success','Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','Category deleted.');
    }
}
