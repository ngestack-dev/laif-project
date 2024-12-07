<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rating;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function accountDetails()
    {
        $auth = Auth::user();
        return view('user.account-details', compact('auth'));
    }

    public function updateAccountDetails(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|digits_between:10,13|unique:users',
            'email'  => 'required|email|max:255|unique:users,email,' . Auth::id(), // Pastikan email unik kecuali milik user saat ini
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Update data user
        $user->name = $validatedData['name'];
        $user->mobile = $validatedData['mobile'];
        $user->email = $validatedData['email'];
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Account details updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Periksa apakah password lama cocok
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function address()
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();
        return view('user.address', compact('address'));
    }

    public function addAddress(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'zip_code' => 'required|numeric|digits:5',
            'city' => 'required',
            'province' => 'required',
        ]);

        $address = new Address();
        $address->user_id = $user_id;
        $address->name = $request->name;
        $address->mobile = $request->mobile;
        $address->address = $request->address;
        $address->zip_code = $request->zip_code;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->isdefault = true;
        $address->save();

        return redirect()->back()->with('success', 'Address added successfully!');
    }
    public function editAddress()
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();
        return view('user.edit-address', compact('address'));
    }

    public function updateAddress(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'zip_code' => 'required|numeric|digits:5',
            'city' => 'required',
            'province' => 'required',
        ]);

        $address->name = $request->name;
        $address->mobile = $request->mobile;
        $address->address = $request->address;
        $address->zip_code = $request->zip_code;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->save();

        return redirect()->route('user.address')->with('success', 'Address updated successfully!');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function orderDetails($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $order_id)->first();
        $ratedProducts = Rating::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();
        if ($order) {
            $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order_id)->first();
            return view('user.order-details', compact('order', 'orderItems', 'transaction', 'ratedProducts'));
        } else {
            return redirect()->route('user.orders')->with('error', 'Order not found');
        }
    }

    public function cancelOrder($order_id)
    {
        $order = Order::find($order_id);
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        return redirect()->back()->with('success', 'Order canceled successfully');
    }

    public function receivedOrder($order_id)
    {
        $order = Order::find($order_id);
        $order->status = 'received';
        $order->received_date = Carbon::now();
        $order->save();
        

        return redirect()->back()->with('success', 'Thank you for your order');
    }

    public function rating(Request $request)
    {
        $ratings = $request->input('ratings'); // Mengambil array rating

        foreach ($ratings as $rating) {
            // Menyimpan rating untuk setiap produk
            Rating::updateOrCreate(
                ['user_id' => auth()->id(), 'product_id' => $rating['product_id']],
                ['stars' => $rating['stars']]
            );
        }

        return response()->json(['message' => 'Ratings submitted successfully.']);
    }
}
