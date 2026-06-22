<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    /**
     * Display the payment settings form.
     */
    public function edit()
    {
        $user = auth()->user();
        $settings = $user->payment_settings ?? [
            'stripe_key' => '',
            'stripe_secret' => '',
            'stripe_sandbox' => false,
            'paypal_client_id' => '',
            'paypal_secret' => '',
            'paypal_sandbox' => false,
        ];

        return view('business.settings.payment', compact('user', 'settings'));
    }

    /**
     * Update the payment settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'preferred_gateway' => ['nullable', 'string', 'in:stripe,paypal'],
            'settings' => ['required', 'array'],
            'settings.stripe_key' => ['nullable', 'string'],
            'settings.stripe_secret' => ['nullable', 'string'],
            'settings.stripe_sandbox' => ['nullable'],
            'settings.paypal_client_id' => ['nullable', 'string'],
            'settings.paypal_secret' => ['nullable', 'string'],
            'settings.paypal_sandbox' => ['nullable'],
        ]);

        $settings = $request->settings;
        $settings['stripe_sandbox'] = $request->input('settings.stripe_sandbox') === '1';
        $settings['paypal_sandbox'] = $request->input('settings.paypal_sandbox') === '1';

        auth()->user()->update([
            'preferred_gateway' => $request->preferred_gateway,
            'payment_settings' => $settings,
        ]);

        return redirect()->route('business.settings.payment.edit')->with('status', 'Payment settings updated successfully!');
    }
}
