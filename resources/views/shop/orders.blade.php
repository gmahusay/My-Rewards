<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Orders') }}
            </h2>
            <a href="{{ route('shop.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to Shop</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-6">
                @forelse ($orders as $order)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <span class="text-sm text-gray-500">Order #{{ $order->id }}</span>
                                <h3 class="text-lg font-bold">{{ $order->created_at->format('M d, Y h:i A') }}</h3>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase">{{ $order->status }}</span>
                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    Total: 
                                    @if($order->payment_method === 'cash')
                                        <span class="text-indigo-600 font-bold">${{ number_format($order->total_cash, 2) }}</span>
                                    @else
                                        <span class="text-amber-600 font-bold">{{ number_format($order->total_points) }} pts</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="p-6">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-4">
                                    @if($item->product->image_path)
                                        <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}" class="h-12 w-12 rounded object-cover shadow-sm">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-50 flex items-center justify-center text-gray-300">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold">
                                            @if($order->payment_method === 'cash')
                                                ${{ number_format($item->price_cash * $item->quantity, 2) }}
                                            @else
                                                {{ number_format($item->price_points * $item->quantity) }} pts
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 rounded-lg shadow text-center border border-gray-100">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't purchased anything yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
