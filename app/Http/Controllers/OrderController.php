<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where("user_id", Auth::id())->with("orderItems")->get();
        return view("orders.index", compact("orders"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cart = Cart::where("user_id", Auth::id())->with("cartItems")->first();
        return view("cart.checkout", compact("cart"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = Cart::where("user_id", Auth::id())->with("cartItems")->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route("cart.index")->with("error", "Cart is empty.");
        }
        $total = 0;
        $orderItems = [];
        $request->validate([
            "shipping_address" => "required",
        ]);
        DB::beginTransaction();
        try {
            foreach ($cart->cartItems as $item) {
                $product = Product::find($item->product_id);
                if (!$product) {
                    return redirect()->route("cart.index")->with("error", "Product" . $product->name . "does not exist anymore.");
                }
                if ($item->quantity > $product->stock) {
                    return redirect()->route("cart.index")->with("error", "Quantity of" . $item->product->name . "is greater than stock");
                }
                $subtotal = ($product->price) * $item->quantity;
                $total += $subtotal;

                $orderItems[] = [
                    "product_id" => $item->product_id,
                    "quantity" => $item->quantity,
                    "subtotal" => ($product->price) * $item->quantity,
                    "product_name" => $item->product->name
                ];



                $product->stock -= $item->quantity;
                $product->save();
            }


            $order = Order::create([
                "user_id" => Auth::id(),
                "total_price" => $total,
                "shipping_address" => $request->shipping_address,
                "status" => "pending"
            ]);
            foreach ($orderItems as $item) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item['product_id'],
                    "quantity" => $item['quantity'],
                    "product_name" => $item['product_name'],
                    "subtotal" => $item['subtotal']
                ]);
            }
            DB::commit();
            $cart->cartItems()->delete();
            return redirect()->route("orders.index")->with("success", "Order created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("cart.index")->with("error", "Error:" . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        if ($order->user_id != Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Order does not belong to user');
        }
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        if ($order->status != 'pending') {
            return redirect()->route('order.index')->with('error', 'Order cannot be editted');
        }
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'shipping_address' => "required"
        ]);
        $order = Order::findOrFail($id);
        $order->update($request->all());
        if ($order->status != 'pending' || $order->user_id != Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Order cannot be editted');
        }
        return redirect()->route('orders.index')->with('success', 'Order editted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id != Auth::id()) {
            return redirect()->route('order.index')->with('error', 'Order cannot be editted');
        }
        $order->orderItems()->delete();
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order  deleted  successfully');
    }
}
