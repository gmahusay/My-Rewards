<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Redirect to simulated Stripe gateway.
     */
    public function stripe(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('shop.index')->with('error', 'Order is not in pending status.');
        }

        $business = $order->business;
        $isSandbox = $business->payment_settings['stripe_sandbox'] ?? false;

        return view('payment.mock_gateway', [
            'order' => $order,
            'gateway' => 'Stripe',
            'isSandbox' => $isSandbox,
            'success_url' => route('payment.stripe.success', $order->id),
            'cancel_url' => route('payment.stripe.cancel', $order->id),
        ]);
    }

    /**
     * Handle Stripe success callback.
     */
    public function stripeSuccess(Order $order)
    {
        return $this->completeOrder($order, 'Stripe');
    }

    /**
     * Handle Stripe cancel callback.
     */
    public function stripeCancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return redirect()->route('cart.index')->with('error', 'Stripe payment was cancelled.');
    }

    /**
     * Redirect to simulated PayPal gateway.
     */
    public function paypal(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('shop.index')->with('error', 'Order is not in pending status.');
        }

        $business = $order->business;
        $isSandbox = $business->payment_settings['paypal_sandbox'] ?? false;

        return view('payment.mock_gateway', [
            'order' => $order,
            'gateway' => 'PayPal',
            'isSandbox' => $isSandbox,
            'success_url' => route('payment.paypal.success', $order->id),
            'cancel_url' => route('payment.paypal.cancel', $order->id),
        ]);
    }

    /**
     * Handle PayPal success callback.
     */
    public function paypalSuccess(Order $order)
    {
        return $this->completeOrder($order, 'PayPal');
    }

    /**
     * Handle PayPal cancel callback.
     */
    public function paypalCancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return redirect()->route('cart.index')->with('error', 'PayPal payment was cancelled.');
    }

    /**
     * Internal method to complete the order.
     */
    protected function completeOrder(Order $order, $gatewayName)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('shop.orders')->with('status', 'Order was already processed.');
        }

        try {
            DB::beginTransaction();

            // 1. Final Stock Check & Deduction
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception("Product '{$product->name}' became out of stock during payment.");
                }
                $product->decrement('stock_quantity', $item->quantity);
            }

            // 2. Update Order Status
            $order->update(['status' => 'completed']);

            DB::commit();

            // Clear Cart (if not already cleared)
            session()->forget('cart');

            // Update Gamification Progress for Purchase per item
            $gamification = app(\App\Services\Gamification\GamificationService::class);
            foreach ($order->items as $item) {
                $gamification->updateCampaignProgress(
                    $order->user_id,
                    'purchase',
                    $item->quantity,
                    $item->product_id
                );
            }

            return redirect()->route('shop.orders')->with('status', "Payment via {$gatewayName} successful! Your order is completed.");

        } catch (\Exception $e) {
            DB::rollBack();
            $order->update(['status' => 'failed']);
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }
}
