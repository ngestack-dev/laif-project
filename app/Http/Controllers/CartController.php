<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::Instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::Instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        if (!$address) {
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
        }

        $this->setAmountforCheckout();

        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = Session::get('checkout')['subtotal'];
        // $order->discount = Session::get('checkout')['discount'];
        $subtotal = Session::get('checkout')['subtotal'];
        $taxAmount = $subtotal * 0.10;
        $order->tax = $taxAmount;
        $order->total = $order->subtotal + $order->tax;
        $order->name = $address->name;
        $order->phone = $address->mobile;
        $order->address = $address->address;
        $order->zip_code = $address->zip_code;
        $order->city = $address->city;
        $order->province = $address->province;
        $order->save();


        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->save();
        }

        if ($request->mode == 'bank') {
            // return redirect()->back()->with('info', 'Bank payment is not available yet');
        } elseif ($request->mode == 'e_wallet') {
            // return redirect()->back()->with('info', 'E-wallet payment is not available yet');
        } elseif ($request->mode == 'cod') {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->status = 'pending';
            $transaction->save();
        } else {
            return redirect()->back()->with('error', 'Must select one of the payment method');
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);
        return redirect()->route('cart.order.confirmation');
    }

    public function setAmountforCheckout()
    {
        if (!Cart::instance('cart')->content()->count() > 0) {
            Session::forget('checkout');
            return;
        }

        Session::put('checkout', [
            'discount' => 0,
            'subtotal' => Cart::instance('cart')->subtotal(),
            'tax' => Cart::instance('cart')->tax(),
            'total' => Cart::instance('cart')->total(),
        ]);
    }

    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}
