<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Claims') }}
            </h2>
            @php $routePrefix = Auth::user()->hasRole('employee') ? 'employee' : 'customer'; @endphp
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="group relative bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                        <a href="{{ route($routePrefix . '.claims.category', $category) }}" class="relative block cursor-pointer">
                            @if($category->image_path)
                                <div class="h-40 w-full overflow-hidden">
                                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                    <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                            @endif
                            
                            @if($category->end_date && $category->end_date->isPast())
                                <div class="absolute inset-0 bg-white/80 flex items-center justify-center backdrop-blur-[1px]">
                                    <span class="px-4 py-2 bg-red-100 text-red-800 font-bold rounded-full border border-red-200 shadow-sm uppercase tracking-wider text-sm">Expired</span>
                                </div>
                            @endif
                        </a>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                </div>
                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                    {{ number_format($category->points_reward) }} pts
                                </span>
                            </div>

                            <a href="{{ route($routePrefix . '.claims.category', $category) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition truncate" title="{{ $category->name }}">{{ $category->name }}</h3>
                            </a>

                            @if($category->end_date)
                                <div class="flex items-center text-sm text-gray-500 mb-3 {{ $category->end_date->isPast() ? 'text-red-500 font-bold' : '' }}">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Deadline: {{ $category->end_date->format('M d, Y') }}
                                </div>
                            @elseif($category->description)
                                 <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Info
                                </div>
                            @endif

                            <p class="text-sm text-gray-600 mb-6 h-10 line-clamp-2">
                                {{ $category->description ?: 'No description provided.' }}
                            </p>
                            
                            <div class="pt-4 border-t border-gray-50 mt-auto">
                                @if($category->end_date && $category->end_date->isPast())
                                    <button disabled class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-400 text-sm font-bold rounded cursor-not-allowed uppercase tracking-widest">
                                        Expired
                                    </button>
                                @else
                                    <a href="{{ route($routePrefix . '.claims.create', ['category_id' => $category->id]) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                                        Start Claim
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded-lg shadow-sm border border-gray-100">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No categories available</h3>
                        <p class="mt-1 text-sm text-gray-500">The business hasn't set up any claim categories yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
