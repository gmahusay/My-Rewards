<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cart as $id => $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if($item['image_path'])
                                                            <img src="{{ Storage::url($item['image_path']) }}" alt="{{ $item['name'] }}" class="h-10 w-10 rounded object-cover">
                                                        @else
                                                            <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">${{ number_format($item['price_cash'], 2) }}</div>
                                                <div class="text-xs text-amber-600">{{ number_format($item['price_points']) }} pts</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 font-bold">${{ number_format($item['price_cash'] * $item['quantity'], 2) }}</div>
                                                <div class="text-xs text-amber-600 font-bold">{{ number_format($item['price_points'] * $item['quantity']) }} pts</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-50 p-6 rounded-lg">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-lg font-bold text-gray-900">Cart Totals</h3>
                                <p class="text-sm text-gray-600">Review your final amounts before checkout.</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-indigo-600">${{ number_format($totalCash, 2) }}</div>
                                <div class="text-lg font-bold text-amber-600">{{ number_format($totalPoints) }} pts Total</div>
                            </div>
                        </div>

                        <form action="{{ route('shop.checkout') }}" method="POST" class="mt-8">
                            @csrf
                            <div class="bg-white border rounded-lg p-6 mb-8">
                                <h3 class="text-lg font-bold mb-4">Payment Method</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition border-indigo-200 bg-indigo-50">
                                        <input type="radio" name="payment_method" value="cash" checked class="text-indigo-600 focus:ring-indigo-500">
                                        <div class="ml-3 flex-1">
                                            <span class="font-bold text-gray-900 block">Online Payment (${{ number_format($totalCash, 2) }})</span>
                                            
                                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                @php 
                                                    $branding = Auth::user()->getBrandingBusiness();
                                                    $settings = $branding->payment_settings ?? [];
                                                    $hasStripe = !empty($settings['stripe_key']);
                                                    $hasPayPal = !empty($settings['paypal_client_id']);
                                                @endphp

                                                @if($hasStripe)
                                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer bg-white hover:border-indigo-500 transition">
                                                        <input type="radio" name="gateway" value="stripe" {{ ($branding->preferred_gateway === 'stripe' || !$hasPayPal) ? 'checked' : '' }} class="text-indigo-600">
                                                        <span class="ml-2 font-medium flex items-center">
                                                            <svg class="h-5 w-5 mr-1 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M13.911 10.32c-.854-.44-1.63-.615-2.22-.615-.815 0-1.285.348-1.285.918 0 .584.58.855 1.62 1.258 1.455.556 3.444 1.254 3.444 3.513 0 2.457-1.92 4.1-5.14 4.1a10.027 10.027 0 01-3.69-.745l.43-2.617c1.104.584 2.19.86 3.03.86.87 0 1.343-.376 1.343-1.012 0-.675-.626-.95-1.745-1.378C9.526 14.155 7.63 13.43 7.63 11.23c0-2.26 1.83-3.83 4.88-3.83a9.423 9.423 0 013.31.57l-.425 2.35h.516z"/></svg>
                                                            Stripe
                                                        </span>
                                                    </label>
                                                @endif

                                                @if($hasPayPal)
                                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer bg-white hover:border-blue-500 transition">
                                                        <input type="radio" name="gateway" value="paypal" {{ ($branding->preferred_gateway === 'paypal' && $hasPayPal) ? 'checked' : '' }} class="text-blue-600">
                                                        <span class="ml-2 font-medium flex items-center">
                                                            <svg class="h-5 w-5 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20.067 8.178c-.626 3.204-2.85 5.122-6.19 5.122h-.994c-.544 0-.918.344-1.018.84l-1.04 5.222c-.044.204-.194.344-.394.344H7.505c-.328 0-.528-.31-.444-.616l2.126-10.66c.1-.5.474-.842 1.018-.842h3.09c2.345 0 3.96 1.106 4.312 3.59h2.46zM13.298 9.54h-1.61c-.346 0-.585.218-.65.534l-.402 2.01c-.027.13.067.228.2.228h.5c.346 0 .585-.218.65-.534l.402-2.01c.027-.13-.067-.228-.2-.228z"/></svg>
                                                            PayPal
                                                        </span>
                                                    </label>
                                                @endif

                                                @if(!$hasStripe && !$hasPayPal)
                                                    <p class="text-sm text-red-500 italic font-normal">Cash payments are currently disabled by the business.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition border-amber-200 bg-amber-50">
                                        <input type="radio" name="payment_method" value="points" class="text-amber-600 focus:ring-amber-500">
                                        <div class="ml-3">
                                            <span class="font-bold text-amber-900 block">Pay with Points ({{ number_format($totalPoints) }} pts)</span>
                                            <span class="text-sm text-amber-700">Your current balance: {{ number_format(Auth::user()->points) }} pts</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4">
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md font-semibold text-gray-700 bg-white hover:bg-gray-50">
                                    Continue Shopping
                                </a>
                                <button type="submit" class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-white uppercase tracking-widest hover:bg-indigo-700 shadow-lg transition">
                                    Proceed to Checkout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-white p-12 rounded-lg shadow text-center border border-gray-100">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">Your cart is empty</h3>
                    <p class="mt-2 text-gray-500">Looks like you haven't added anything to your cart yet.</p>
                    <div class="mt-8">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
                            Go to Shop
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
