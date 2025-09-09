<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
{
    $brands = Product::query()->where('is_active', true)->pluck('brand')->unique()->filter()->values();
    
    $popularProducts = Product::with('primaryImage')
        ->where('is_active', true)
        ->orderByDesc('stock')
        ->take(6)
        ->get();

    return view('home', [
        'brands' => $brands,
        'popularProducts' => $popularProducts,
    ]);
}
}