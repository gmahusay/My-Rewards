<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display a listing of products available for the user.
     */
    public function index()
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        // Ensure users only see products from their associated business
        if (!$businessId && !$user->hasRole('business')) {
            return view('shop.index', ['products' => collect()]);
        }

        if ($user->hasRole('business')) {
            $businessId = $user->id;
        }

        $products = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->paginate(12);

        return view('shop.index', compact('products'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $user = auth()->user();
        $businessId = $user->hasRole('business') ? $user->id : $user->business_id;

        if ($product->business_id !== $businessId) {
            abort(403);
        }

        return view('shop.show', compact('product'));
    }

    /**
     * Handle the checkout process (from cart).
     */
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = session()->get('cart', []);

        if (count($cart) === 0) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'payment_method' => ['required', 'in:cash,points'],
            'gateway' => ['required_if:payment_method,cash', 'nullable', 'in:stripe,paypal'],
        ]);

        $paymentMethod = $request->payment_method;
        $gateway = $request->gateway;
        $totalCash = 0;
        $totalPoints = 0;
        $businessId = null;

        // Calculate totals and validate stock
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if (!$product || $product->stock_quantity < $details['quantity']) {
                return redirect()->route('cart.index')->with('error', "Product '{$details['name']}' is out of stock or unavailable.");
            }
            
            if (!$businessId) $businessId = $product->business_id;
            
            $totalCash += $product->price_cash * $details['quantity'];
            $totalPoints += $product->price_points * $details['quantity'];
        }

        $business = \App\Models\User::find($businessId);

        try {
            DB::beginTransaction();

            // Case 1: Pay with Points (Instant Completion)
            if ($paymentMethod === 'points') {
                if ($user->points < $totalPoints) {
                    throw new \Exception("Insufficient points balance.");
                }
                $user->deductPoints($totalPoints);
                
                // Track point transaction
                $user->addPoints(-$totalPoints, "Purchased " . count($cart) . " items from shop", $business);

                $order = Order::create([
                    'user_id' => $user->id,
                    'business_id' => $businessId,
                    'total_cash' => $totalCash,
                    'total_points' => $totalPoints,
                    'payment_method' => 'points',
                    'status' => 'completed',
                ]);

                $gamification = app(\App\Services\Gamification\GamificationService::class);
                foreach ($cart as $id => $details) {
                    $product = Product::find($id);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $details['quantity'],
                        'price_cash' => $product->price_cash,
                        'price_points' => $product->price_points,
                    ]);
                    $product->decrement('stock_quantity', $details['quantity']);

                    $gamification->updateCampaignProgress(
                        $order->user_id,
                        'purchase',
                        $details['quantity'],
                        $id
                    );
                }

                DB::commit();
                session()->forget('cart');
                return redirect()->route('shop.orders')->with('status', 'Purchase completed successfully with points!');
            }

            // Case 2: Pay with Cash (Pending -> Redirection)
            if ($paymentMethod === 'cash') {
                // Ensure business has settings for the selected gateway
                $settings = $business->payment_settings;
                if ($gateway === 'stripe' && (empty($settings['stripe_key']) || empty($settings['stripe_secret']))) {
                    throw new \Exception("Stripe is not configured for this business.");
                }
                if ($gateway === 'paypal' && (empty($settings['paypal_client_id']) || empty($settings['paypal_secret']))) {
                    throw new \Exception("PayPal is not configured for this business.");
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'business_id' => $businessId,
                    'total_cash' => $totalCash,
                    'total_points' => $totalPoints,
                    'payment_method' => $gateway, // Storing gateway name as payment method
                    'status' => 'pending',
                ]);

                foreach ($cart as $id => $details) {
                    $product = Product::find($id);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $details['quantity'],
                        'price_cash' => $product->price_cash,
                        'price_points' => $product->price_points,
                    ]);
                    // Don't deduct stock yet for pending orders
                }

                DB::commit();
                
                // Redirection to PaymentController
                if ($gateway === 'stripe') {
                    return redirect()->route('payment.stripe', $order->id);
                } else {
                    return redirect()->route('payment.paypal', $order->id);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the user's order history.
     */
    public function orders()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('shop.orders', compact('orders'));
    }
}
