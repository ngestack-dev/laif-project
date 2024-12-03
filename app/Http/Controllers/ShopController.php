<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        return view('shop', compact('products'));
            
    }

    public function product_details($product_slug) {
        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>', $product_slug)->get()->take(8);

        $ratings = $product->ratings()->with('user')->get(); // Menambahkan relasi 'user' jika diperlukan
        $averageRating = $product->ratings()->avg('stars'); // Menghitung rata-rata rating
        $totalRatings = $product->ratings()->count(); // Menghitung total rating
        
        return view('details', compact('product', 'rproducts', 'ratings', 'averageRating', 'totalRatings'));
    }
}
