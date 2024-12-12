<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Exports\offlineOrdersExport;
use App\Models\About;
use App\Models\ActivityLog;
use App\Models\OfflineOrder;
use App\Models\OfflineProduct;
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
use Maatwebsite\Excel\Facades\Excel;

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

            ActivityLog::create([
                'admin_id' => Auth('admin')->id(),
                'activity' => 'logged in',
            ]);

            return redirect()->route('admin.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function AdminLogout(Request $request)
    {
        ActivityLog::create([
            'admin_id' => Auth('admin')->id(),
            'activity' => 'logged out',
        ]);
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function index()
    {
        $ordersCount = Order::where('status', 'received')->count();
        $totalOrdersAmount = Order::where('status', 'received')->sum('total');
        $offlineOrdersCount = OfflineOrder::count();
        $offlineOrdersAmount = OfflineOrder::sum('total');
        $recentOrder = Order::latest()->first();
        $recentOfflineOrder = OfflineOrder::where('admin_id', Auth::id())->latest()->first();

        return view('admin.index', compact('ordersCount', 'totalOrdersAmount', 'offlineOrdersCount', 'offlineOrdersAmount', 'recentOrder', 'recentOfflineOrder'));
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'ASC')->paginate(10);
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
        if ($request->order_status == 'delivered') {
            $orderItems = OrderItem::where('order_id', $request->order_id)->get();
            foreach ($orderItems as $orderItem) {
                // Kurangi quantity produk sesuai quantity di OrderItem
                Product::where('id', $orderItem->product_id)->decrement('quantity', $orderItem->quantity);
            }
            $order->delivered_date = Carbon::now();
        } else if ($request->order_status == 'canceled') {
            $order->canceled_date = Carbon::now();
        }
        $order->save();

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Updated an order: ' . $order->id,
        ]);

        if ($request->order_status == 'delivered') {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            $transaction->status = 'success';
            $transaction->save();
        }

        return back()->with('status', 'Order status has been updated successfully');
    }

    public function orderDelete($order_id)
    {
        $order = Order::find($order_id);
        $order->delete();
        return redirect()->route('admin.orders')->with('status', 'Order deleted successfully.');
    }

    public function exportOrdersXlsx()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    /**
     * Export data to CSV (.csv).
     */
    public function exportOrdersCsv()
    {
        return Excel::download(new OrdersExport, 'orders.csv', \Maatwebsite\Excel\Excel::CSV);
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
            'email_laif' => 'required|string',
            'phone_laif' => 'required|string',
            'instagram' => 'required|string',
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

    public function offlineOrders()
    {
        $orders = OfflineOrder::when(Auth::id() != 1, function ($query) {
            return $query->where('admin_id', Auth::id());
        })
            ->orderBy('created_at', 'DESC')
            ->paginate(12);
        return view('admin.offline-orders', compact('orders'));
    }

    public function addOfflineOrder()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        $oproducts = OfflineProduct::where('admin_id', Auth::id())->pluck('quantity', 'product_id');
        return view('admin.add-offline-order', compact('products', 'oproducts'));
    }

    public function storeOfflineOrder(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

        // Filter produk yang kuantitasnya lebih dari 0
        $filteredProducts = array_filter($request->input('products'), function ($product) {
            return $product['quantity'] > 0;
        });

        // Jika tidak ada produk yang valid, kembalikan dengan error
        if (empty($filteredProducts)) {
            return redirect()->back()->withErrors(['products' => 'Please add at least one product with a quantity greater than 0.']);
        }

        foreach ($filteredProducts as $product) {
            if (auth()->user()->hasRole('super-admin')) {
                $productData = Product::find($product['id']);
                $availableQuantity = $productData->quantity;
            } else {
                $productData = OfflineProduct::where('product_id', $product['id'])
                    ->where('admin_id', Auth::id())
                    ->first();
                $availableQuantity = $productData->quantity ?? 0;
            }

            if ($product['quantity'] > $availableQuantity) {
                return redirect()->back()->withErrors([
                    'products' => "The requested quantity for product '{$productData->product->name}' exceeds the available stock.",
                ]);
            }
        }

        // Simpan order ke dalam database
        $order = new OfflineOrder();
        $order->admin_id = Auth::id();
        $order->total = $this->calculateSubtotal($filteredProducts);
        // $order->tax = $this->calculateTax($order->subtotal);
        // $order->total = $order->subtotal + $order->tax;
        $order->save();

        // Simpan order items ke dalam database
        foreach ($filteredProducts as $product) {
            // Buat instance OrderItem baru untuk setiap produk
            $orderItem = new OrderItem();
            $orderItem->offline_order_id = $order->id;
            $orderItem->product_id = $product['id'];
            $orderItem->quantity = $product['quantity'];
            $orderItem->price = $product['price'];

            if (auth()->user()->hasRole('super-admin')) {
                Product::where('id', $product['id'])
                ->decrement('quantity', $product['quantity']);
            } else {
                OfflineProduct::where('product_id', $product['id'])
                ->decrement('quantity', $product['quantity']);
            }

            // Simpan OrderItem setelah melakukan decrement
            $orderItem->save();
        }

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Create an offline order with total: Rp' . number_format($order->total, 3, '.', '.'),
        ]);

        // Redirect atau memberi response setelah berhasil
        return redirect()->route('admin.offline.orders')->with('success', 'Offline order created successfully.');
    }

    public function offlineOrderDelete($offline_order_id)
    {
        $order = OfflineOrder::find($offline_order_id);
        $order->delete();
        return redirect()->route('admin.offline.orders')->with('success', 'Offline order deleted successfully.');
    }

    public function offlineOrderDeleteAll()
    {
        $orders = OfflineOrder::all();
        foreach ($orders as $order) {
            $order->delete();
        }
        return redirect()->route('admin.offline.orders')->with('success', 'All offline orders deleted successfully.');
    }


    private function calculateSubtotal($products)
    {
        $subtotal = 0;
        foreach ($products as $product) {
            $subtotal += $this->getProductPrice($product['id']) * $product['quantity'];
        }
        return $subtotal;
    }

    // Fungsi untuk mendapatkan harga produk
    private function getProductPrice($product_id)
    {
        // Mengambil harga produk dari database atau model
        $product = \App\Models\Product::find($product_id);
        return $product ? $product->sale_price ?: $product->regular_price : 0;
    }

    // Fungsi untuk menghitung pajak (contoh sederhana 10%)
    private function calculateTax($subtotal)
    {
        return $subtotal * 0.10; // 10% pajak
    }

    public function offlineOrderDetails($offline_order_id)
    {
        $order = OfflineOrder::find($offline_order_id);
        $orderItems = OrderItem::where('offline_order_id', $offline_order_id)->orderBy('id')->paginate(12);
        return view('admin.offline-order-details', compact('order', 'orderItems'));
    }

    public function exportOfflineOrdersXlsx()
    {
        return Excel::download(new OfflineOrdersExport, 'offline-orders.xlsx');
    }

    public function exportOfflineOrdersCsv()
    {
        return Excel::download(new OfflineOrdersExport, 'offline-orders.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function searchProduct(Request $request)
    {
        // Ambil query dari form pencarian
        $query = $request->input('query');

        // Filter data berdasarkan query jika ada
        $products = Product::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('SKU', 'like', '%' . $query . '%')
                    ->orWhere('regular_price', 'like', '%' . $query . '%')
                    ->orWhere('stock_status', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Pagination, Anda dapat menyesuaikan jumlahnya

        // Kembalikan ke view dengan data admins
        return view('admin.products', compact('products'));
    }


    public function searchOrders(Request $request)
    {
        // Ambil query dari form pencarian
        $query = $request->input('query');

        // Filter data berdasarkan query jika ada
        $orders = Order::query()
            ->with('user')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('user', function ($subQuery) use ($query) {
                    $subQuery->where('name', 'like', '%' . $query . '%'); // Cari berdasarkan nama user
                })
                    ->orWhere('id', 'like', '%' . $query . '%')
                    ->orWhere('status', 'like', '%' . $query . '%')
                    ->orWhereRaw("DATE_FORMAT(created_at, '%d %b %Y %H:%i') LIKE ?", ['%' . $query . '%']);
            })
            ->paginate(10); // Pagination, Anda dapat menyesuaikan jumlahnya

        // Kembalikan ke view dengan data admins
        return view('admin.orders', compact('orders'));
    }

    public function searchOfflineOrders(Request $request)
    {
        // Ambil query dari form pencarian
        $query = $request->input('query');

        // Filter data berdasarkan query jika ada
        $orders = OfflineOrder::query()
            ->when($query, function ($q) use ($query) {
                $q->where('id', 'like', '%' . $query . '%')
                    ->orWhere('total', 'like', '%' . $query . '%')
                    ->orWhereRaw("DATE_FORMAT(created_at, '%d %b %Y %H:%i') LIKE ?", ['%' . $query . '%']);
            })
            ->paginate(10); // Pagination, Anda dapat menyesuaikan jumlahnya

        // Kembalikan ke view dengan data admins
        return view('admin.offline-orders', compact('orders'));
    }

    public function offlineProducts()
    {
        $products = Product::all();
        $oproducts = OfflineProduct::where('admin_id', Auth::id())->pluck('quantity', 'product_id');

        return view('admin.offline-products', compact('products', 'oproducts'));
    }

    public function addOfflineProduct()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        $oproducts = OfflineProduct::where('admin_id', Auth::id())->pluck('quantity', 'product_id');
        return view('admin.edit-offline-product', compact('products', 'oproducts'));
    }

    public function storeOfflineProduct(Request $request)
    {
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

        foreach ($validatedData['products'] as $productData) {
            // Gunakan where untuk mencari dan lakukan update manual jika quantity = 0
            OfflineProduct::updateOrCreate(
                [
                    'product_id' => $productData['id'],
                    'admin_id' => Auth::id(),
                ],
                [
                    'quantity' => $productData['quantity'],
                ]
            );
        }

        ActivityLog::create([
            'admin_id' => auth()->id(),
            'activity' => 'Updated Offline Product quantities ',
        ]);

        return redirect()->route('admin.offline.products')->with('status', 'Offline product quantities updated successfully!');
    }
}
