<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;

class HomeController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function about()
    {
        $about = About::first();
        return view('about', compact('about'));
    }

    
}
