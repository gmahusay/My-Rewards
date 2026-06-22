<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('KPI Goals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route(request()->routeIs('employee.*') ? 'employee.kpis.show' : 'customer.kpis.show', $category) }}" 
                       class="block bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 flex flex-col h-full hover:shadow-md transition duration-150 ease-in-out group">
                        @if($category->image_path)
                            <div class="h-40 w-full overflow-hidden">
                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-150">
                            </div>
                        @else
                            <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600">{{ $category->name }}</h4>
                            </div>
                            
                            <div class="mb-4">
                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                    {{ number_format($category->points_reward) }} pts
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-3 mb-4">{{ $category->description }}</p>

                            <div class="text-xs text-gray-500 mb-4">
                                <div>{{ $category->start_date->format('M d, Y') }} - {{ $category->end_date->format('M d, Y') }}</div>
                            </div>

                            <div class="mt-auto pt-4 flex items-center text-indigo-600 font-semibold text-sm">
                                Submit KPI
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-lg shadow-sm">
                        <svg class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <p class="text-lg font-medium">No active KPI goals at the moment.</p>
                        <p class="text-sm">Check back later for new opportunities!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
