<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) =>
                $q->where('slug', $request->string('category'))
            );
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->string('brand'));
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [
                (float) $request->min_price,
                (float) $request->max_price
            ]);
        }

        $products   = $query->orderByDesc('id')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('shop.index', compact('products', 'categories'));
    }

    // GET /shop/{slug}
    public function show(string $slug)
    {
        $product = Product::with('primaryImage')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('shop.show', compact('product'));
    }
}
