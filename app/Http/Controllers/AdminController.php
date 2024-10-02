<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        return view('admin.product-add');
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'nullable',
            'SKU' => 'required',
            'quantity' => 'required',
            'image' => 'required | mimes:png,jpg,jpeg | max: 2048',
            'featured' => 'required',
            'stock_status' => 'required',
        ], [
            'image.mimes' => 'Hanya file dengan ekstensi jpeg, png, jpg, atau gif yang diizinkan.',
            'image.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        $product = new Product();

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price; 
        $product->SKU = $request->SKU;
        $product->quantity = $request->quantity;
        $product->featured = $request->featured;
        $product->stock_status = $request->stock_status;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;            
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')) 
        {
            $allowedFileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedFileExtion);
                if ($gcheck) {
                    $gfileName = $current_timestamp . '-' . $counter . '.' . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',$gallery_arr);    
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been added successfully');    
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPathThumbnails = public_path('/uploads/products/thumbnails');
        $destinationPath = public_path('/uploads/products');
        $img = Image::read($image->path());
        $img->cover(540,689,"top");
        $img->resize(540,689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnails . '/' . $imageName);
    }

}
