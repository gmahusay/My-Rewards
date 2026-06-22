<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Gateway Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('business.settings.payment.update') }}">
                        @csrf
                        @method('PATCH')

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">General Settings</h3>
                            <div class="mt-4">
                                <x-input-label for="preferred_gateway" :value="__('Preferred Payment Gateway')" />
                                <select id="preferred_gateway" name="preferred_gateway" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="" {{ !$user->preferred_gateway ? 'selected' : '' }}>None</option>
                                    <option value="stripe" {{ $user->preferred_gateway === 'stripe' ? 'selected' : '' }}>Stripe</option>
                                    <option value="paypal" {{ $user->preferred_gateway === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                </select>
                                <x-input-error :messages="$errors->get('preferred_gateway')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Stripe Settings -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="h-6 w-6 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M13.911 10.32c-.854-.44-1.63-.615-2.22-.615-.815 0-1.285.348-1.285.918 0 .584.58.855 1.62 1.258 1.455.556 3.444 1.254 3.444 3.513 0 2.457-1.92 4.1-5.14 4.1a10.027 10.027 0 01-3.69-.745l.43-2.617c1.104.584 2.19.86 3.03.86.87 0 1.343-.376 1.343-1.012 0-.675-.626-.95-1.745-1.378C9.526 14.155 7.63 13.43 7.63 11.23c0-2.26 1.83-3.83 4.88-3.83a9.423 9.423 0 013.31.57l-.425 2.35h.516z"/></svg>
                                    Stripe Configuration
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="stripe_key" :value="__('Stripe Publishable Key')" />
                                        <x-text-input id="stripe_key" name="settings[stripe_key]" type="text" class="mt-1 block w-full" :value="$settings['stripe_key']" />
                                    </div>
                                    <div>
                                        <x-input-label for="stripe_secret" :value="__('Stripe Secret Key')" />
                                        <x-text-input id="stripe_secret" name="settings[stripe_secret]" type="password" class="mt-1 block w-full" :value="$settings['stripe_secret'] ?? ''" />
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <input type="checkbox" id="stripe_sandbox" name="settings[stripe_sandbox]" value="1" {{ ($settings['stripe_sandbox'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label for="stripe_sandbox" class="ml-2 text-sm text-gray-600">{{ __('Use Sandbox Mode') }}</label>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal Settings -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="h-6 w-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20.067 8.178c-.626 3.204-2.85 5.122-6.19 5.122h-.994c-.544 0-.918.344-1.018.84l-1.04 5.222c-.044.204-.194.344-.394.344H7.505c-.328 0-.528-.31-.444-.616l2.126-10.66c.1-.5.474-.842 1.018-.842h3.09c2.345 0 3.96 1.106 4.312 3.59h2.46zM13.298 9.54h-1.61c-.346 0-.585.218-.65.534l-.402 2.01c-.027.13.067.228.2.228h.5c.346 0 .585-.218.65-.534l.402-2.01c.027-.13-.067-.228-.2-.228z"/></svg>
                                    PayPal Configuration
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="paypal_client_id" :value="__('PayPal Client ID')" />
                                        <x-text-input id="paypal_client_id" name="settings[paypal_client_id]" type="text" class="mt-1 block w-full" :value="$settings['paypal_client_id']" />
                                    </div>
                                    <div>
                                        <x-input-label for="paypal_secret" :value="__('PayPal Secret')" />
                                        <x-text-input id="paypal_secret" name="settings[paypal_secret]" type="password" class="mt-1 block w-full" :value="$settings['paypal_secret'] ?? ''" />
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <input type="checkbox" id="paypal_sandbox" name="settings[paypal_sandbox]" value="1" {{ ($settings['paypal_sandbox'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label for="paypal_sandbox" class="ml-2 text-sm text-gray-600">{{ __('Use Sandbox Mode') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('Save Settings') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
