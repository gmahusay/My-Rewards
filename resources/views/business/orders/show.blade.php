<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('business.orders.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">
                &lsaquo; Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Summary & Items -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Purchased Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (Unit)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($item->product->image_path)
                                                        <img src="{{ Storage::url($item->product->image_path) }}" alt="" class="h-8 w-8 rounded mr-3 object-cover">
                                                    @endif
                                                    <span class="text-sm font-medium text-gray-900">{{ $item->product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($item->price_cash, 2) }} / {{ number_format($item->price_points) }} pts
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                x {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                                ${{ number_format($item->price_cash * $item->quantity, 2) }} <br>
                                                <span class="text-xs text-amber-600">{{ number_format($item->price_points * $item->quantity) }} pts</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 font-bold">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-700">Order Totals:</td>
                                        <td class="px-6 py-3 text-right text-indigo-600">
                                            ${{ number_format($order->total_cash, 2) }} <br>
                                            <span class="text-sm text-amber-600">{{ number_format($order->total_points) }} pts</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Customer Info & Status -->
                <div class="space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Customer Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Name</label>
                                <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email</label>
                                <p class="text-sm text-gray-900">{{ $order->user->email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Role</label>
                                <p class="text-sm">
                                    <span class="px-2 py-0.5 rounded text-xs {{ $order->user->hasRole('employee') ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($order->user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Order Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Method</label>
                                <p class="text-sm text-gray-900 uppercase font-semibold">{{ $order->payment_method }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Order Status</label>
                                <p class="mt-1">
                                    @php
                                        $statusClasses = [
                                            'completed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-blue-100 text-blue-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'failed' => 'bg-orange-100 text-orange-800',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Checkout Date</label>
                                <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y @ H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
