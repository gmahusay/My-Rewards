<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 flex flex-col">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-48 w-full object-cover">
                        @else
                            <div class="h-48 w-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                        @endif
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-1">{{ $product->description }}</p>
                            
                            <div class="flex flex-col gap-2 mt-auto">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold text-indigo-600">${{ number_format($product->price_cash, 2) }}</span>
                                        <span class="text-sm text-amber-600 font-medium">{{ number_format($product->price_points) }} pts</span>
                                    </div>
                                    <a href="{{ route('shop.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase">Details</a>
                                </div>
                                
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition ease-in-out duration-150">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-lg shadow text-center border border-gray-100">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products available</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new items.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
