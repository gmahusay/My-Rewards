<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $totalCash = 0;
        $totalPoints = 0;

        foreach ($cart as $item) {
            $totalCash += $item['price_cash'] * $item['quantity'];
            $totalPoints += $item['price_points'] * $item['quantity'];
        }

        return view('shop.cart', compact('cart', 'totalCash', 'totalPoints'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price_cash' => $product->price_cash,
                'price_points' => $product->price_points,
                'image_path' => $product->image_path,
            ];
        }

        session()->put('cart', $cart);

        // Prevent redirect loop if the user is at the add-to-cart URL directly
        if (url()->previous() === url()->current()) {
            return redirect()->route('cart.index')->with('status', 'Product added to cart!');
        }

        return redirect()->back()->with('status', 'Product added to cart!');
    }

    /**
     * Update the quantity of an item in the cart.
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($id);
            $newQuantity = $request->quantity;

            if ($product && $newQuantity <= $product->stock_quantity && $newQuantity > 0) {
                $cart[$id]['quantity'] = $newQuantity;
                session()->put('cart', $cart);
                return redirect()->back()->with('status', 'Cart updated!');
            }
        }

        return redirect()->back()->with('error', 'Invalid quantity or out of stock.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('status', 'Item removed from cart!');
    }
}
