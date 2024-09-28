<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        return view('shop', compact('products'));
            
    }

    // public function product_details($product_slug) {
    public function product_details() {
        // $product = Product::where('slug', $product_slug)->first();
        // $rproducts = Product::where('slug', '<>', $product_slug)->get(8);
        // return view('details', compact('product'));
        return view('details');
    }
}
