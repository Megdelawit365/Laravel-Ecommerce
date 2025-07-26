<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
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
        if (!$cart || $cart->cartItems()->isEmpty()) {
            return redirect()->route("cart.index")->with("error", "Cart is empty.");
        }
        $total = 0;
        $orderItems = [];
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
        $request->validate([
            "shipping_address" => "required",
        ]);
        $cart->cartItems()->delete();

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
        return redirect()->route("orders.index")->with("success", "Order created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
