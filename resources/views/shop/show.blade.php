<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="md:flex gap-8">
                    <div class="md:w-1/2">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg shadow">
                        @else
                            <div class="w-full aspect-square bg-gray-100 flex items-center justify-center text-gray-400 rounded-lg">
                                <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="md:w-1/2 mt-8 md:mt-0 flex flex-col">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                        <p class="text-gray-600 mb-6 text-lg">{{ $product->description }}</p>
                        
                        <div class="bg-indigo-50 p-6 rounded-lg mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-600">Stock Available:</span>
                                <span class="font-bold text-gray-900">{{ $product->stock_quantity }} units</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Cash Price:</span>
                                <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->price_cash, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-indigo-100 pt-2">
                                <span class="text-gray-600">Points Price:</span>
                                <span class="text-2xl font-bold text-amber-600">{{ number_format($product->price_points) }} pts</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition transform active:scale-95 flex items-center justify-center">
                                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
