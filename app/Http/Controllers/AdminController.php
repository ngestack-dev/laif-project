<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function adminLoginForm()
    {
        return view('admin.admin-login'); // Pastikan ada view dengan nama admin/login.blade.php
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function index()
    {
        // $user = Auth::guard('admin')->user();

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

    public function product_view($id)
    {
        $product = Product::find($id);
        return view('admin.product-view', compact('product'));
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
            'image' => 'required | mimes:png,jpg,jpeg | max: 15680',
            'featured' => 'required',
            'stock_status' => 'required',
        ], [
            'image.mimes' => 'Hanya file dengan ekstensi jpeg, png, jpg, atau gif yang diizinkan.',
            'image.max' => 'Ukuran file tidak boleh lebih dari 15MB.',
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
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
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Created a new product: ' . $product->name,
        ]);

        return redirect()->route('admin.products')->with('status', 'Product has been added successfully');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPathThumbnails = public_path('/uploads/products/thumbnails');
        $destinationPath = public_path('/uploads/products');
        $img = Image::read($image->path());
        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnails . '/' . $imageName);
    }

    public function product_edit($id)
    {
        $product = Product::find($id);
        return view('admin.product-edit', compact('product'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'nullable',
            'SKU' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:png,jpg,jpeg | max: 2048',
            'featured' => 'required',
            'stock_status' => 'required',
        ], [
            'image.mimes' => 'Hanya file dengan ekstensi jpeg, png, jpg, atau gif yang diizinkan.',
            'image.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        $product = Product::find($request->id);

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

        if ($request->hasFile('image')) {
            if (File::exists(public_path('/uploads/products') . '/' . $product->image)) {
                File::delete(public_path('/uploads/products') . '/' . $product->image);
            }
            if (File::exists(public_path('/uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('/uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            foreach (explode(',', $product->images) as $ofile) {
                if (File::exists(public_path('/uploads/products') . '/' . $ofile)) {
                    File::delete(public_path('/uploads/products') . '/' . $ofile);
                }
                if (File::exists(public_path('/uploads/products/thumbnails') . '/' . $ofile)) {
                    File::delete(public_path('/uploads/products/thumbnails') . '/' . $ofile);
                }
            }

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
            $gallery_images = implode(',', $gallery_arr);
            $product->images = $gallery_images;
        }

        $product->save();

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Updated a product: ' . $product->name,
        ]);

        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('/uploads/products') . '/' . $product->image)) {
            File::delete(public_path('/uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('/uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('/uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('/uploads/products') . '/' . $ofile)) {
                File::delete(public_path('/uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('/uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('/uploads/products/thumbnails') . '/' . $ofile);
            }
        }

        $product->delete();

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Deleted a product: ' . $product->name,
        ]);

        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully');
    }


    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders')); 
    }

    public function order_details($order_id)
    {
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id', $order_id)->first(); 
        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_order_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        if ($request->order_status == 'delivered') 
        {
            $order->delivered_date = Carbon::now();
        }
        else if ($request->order_status == 'canceled') 
        {
            $order->canceled_date = Carbon::now();
        }
        $order->save();

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Updated a order: ' . $order->name,
        ]);

        if ($request->order_status == 'delivered')
        {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            $transaction->status = 'success';
            $transaction->save();
        }

        return back()->with('status', 'Order status has been updated successfully');
    }

    public function edit_about()
    {
        // Mengambil data about yang pertama (asumsi hanya ada 1 row data)
        $about = About::first();

        return view('admin.about-edit', compact('about'));
    }

    public function update_about(Request $request)
    {
        $validatedData = $request->validate([
            'address' => 'required|string',
            'story' => 'required|string',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'about_laif' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'image.mimes' => 'Hanya file dengan ekstensi jpeg, png, dan jpg yang diizinkan.',
            'image.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        // Mengambil data about yang pertama
        $about = About::first();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/about'), $imageName);
            $validatedData['image'] = $imageName;

            // Jika ada gambar lama, hapus gambar lama
            if ($about && $about->image) {
                unlink(public_path('assets/images/about/' . $about->image));
            }
        }

        // Update data dalam database
        if ($about) {
            $about->update($validatedData);
        } else {
            About::create($validatedData);
        }

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Updated an about ',
        ]);

        return redirect()->route('about.edit')->with('success', 'About updated successfully.');
    }
}