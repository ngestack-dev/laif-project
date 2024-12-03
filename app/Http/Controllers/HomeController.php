<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\Product;

class HomeController extends Controller
{

    public function index()
    {
        $new_product = Product::orderBy('created_at', 'DESC')->first();
        $salesProduct = Product::whereNotNull('sale_price')->get();
        $featuredProduct = Product::where('featured', 1)->get();
        $about = About::first();
        return view('index', compact('new_product', 'salesProduct', 'featuredProduct', 'about'));
    }

    public function about()
    {
        $about = About::first();
        return view('about', compact('about'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name', 'LIKE', "%{$query}%")->get()->take(8);
        return response()->json($results);
    }

    
}
