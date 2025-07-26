<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::where("user_id", Auth::id())->first();
        if (!$cart) {
            $cart = Cart::create(["user_id" => Auth::id()]);
        }
        $cartItems = CartItem::where("cart_id", $cart->id)->get();
        return view("cart.index", compact("cartItems"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "cart_id" => ["required", "exists:carts,id"],
            "product_id" => ["required", "exists:products,id"],
            "quantity" => ["required", "integer", "min:1"],
        ]);
        $cartItem = CartItem::create([
            "cart_id" => $request->cart_id,
            "product_id" => $request->product_id,
            "quantity" => $request->quantity
        ]);
        return redirect()->route("cart.index")->with("success", "Item  added to cart successfully.");
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
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();
        return redirect()->route("cart.index")->with("success", "Item removed from cart successfully");
    }
}
