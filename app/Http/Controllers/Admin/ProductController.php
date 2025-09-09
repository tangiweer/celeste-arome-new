<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Load both category and images relationships for the admin list
        $products = Product::with(['category', 'images'])->latest('id')->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /** Make a unique slug from a base string (optionally ignore an id) */
    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($base);
        $original = $slug;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name'        => ['required','string','max:150'],
            'slug'        => ['nullable','string','max:160'], // uniqueness handled below
            'brand'       => ['nullable','string','max:120'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'is_active'   => ['nullable','boolean'],
            'image'       => ['nullable','image','max:2048'],
        ]);

        // ✅ Safely compute slug even if not provided; ensure uniqueness
        $want        = $request->input('slug', $data['name']);
        $data['slug'] = $this->makeUniqueSlug($want);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        // Remove 'image' from data array before creating product
        unset($data['image']);

        // Create product first
        $product = Product::create($data);

        // Handle image upload separately
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            
            // Create image record
            $product->images()->create([
                'path' => $imagePath,
                'is_primary' => true,
            ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success','Product created.');
    }

    public function show(Product $product)
    {
        // Load the images relationship for the show view
        $product->load(['category', 'images']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Load the images relationship for the edit view
        $product->load('images');
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name'        => ['required','string','max:150'],
            'slug'        => ['nullable','string','max:160'], // uniqueness handled below
            'brand'       => ['nullable','string','max:120'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'is_active'   => ['nullable','boolean'],
            'image'       => ['nullable','image','max:2048'],
        ]);

        // ✅ Keep current slug unless user supplied a new one or name changed;
        //    always ensure uniqueness when changing.
        if ($request->filled('slug')) {
            $want = $request->input('slug');
        } elseif ($data['name'] !== $product->name) {
            $want = $data['name'];
        } else {
            $want = $product->slug ?: $data['name'];
        }
        $data['slug'] = $this->makeUniqueSlug($want, $product->id);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        // Remove 'image' from data array before updating product
        unset($data['image']);

        // Handle image upload separately
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            
            // Delete old primary image if it exists
            $oldPrimaryImage = $product->primaryImage;
            if ($oldPrimaryImage) {
                // Delete the physical file
                if ($oldPrimaryImage->path && !Str::startsWith($oldPrimaryImage->path, ['http://', 'https://'])) {
                    Storage::disk('public')->delete($oldPrimaryImage->path);
                }
                // Delete the database record
                $oldPrimaryImage->delete();
            }
            
            // Create new primary image record
            $product->images()->create([
                'path' => $imagePath,
                'is_primary' => true,
            ]);
        }

        // Update product (without image field)
        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success','Product updated.');
    }

    public function destroy(Product $product)
    {
        // Delete all product images
        foreach ($product->images as $image) {
            if ($image->path && !Str::startsWith($image->path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        $product->delete();

        return back()->with('success','Product deleted.');
    }
}