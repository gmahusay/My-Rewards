<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($category) ? __('My Claims - ') . $category->name : __('My Claims') }}
            </h2>
            @php $routePrefix = Auth::user()->hasRole('employee') ? 'employee' : 'customer'; @endphp
            <a href="{{ route($routePrefix . '.claims.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Submit New Claim
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-none overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($claims as $claim)
                            <div class="group relative bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                                <!-- Card Image -->
                                <a href="{{ route($routePrefix . '.claims.show', $claim) }}" class="block relative">
                                    @if($claim->category && $claim->category->image_path)
                                        <div class="h-40 w-full overflow-hidden">
                                            <img src="{{ Storage::url($claim->category->image_path) }}" alt="{{ $claim->category->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                            <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                    @endif
                                </a>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                        </div>
                                        @if($claim->rewarded_points > 0)
                                            <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                                {{ number_format($claim->rewarded_points) }} pts
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route($routePrefix . '.claims.show', $claim) }}" class="block">
                                        <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition truncate" title="{{ $claim->title }}">{{ $claim->title }}</h3>
                                    </a>
                                    
                                    <div class="flex items-center text-sm text-gray-500 mb-3">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        {{ $claim->created_at->format('M d, Y') }}
                                    </div>

                                    <p class="text-sm text-gray-600 line-clamp-2 mb-4 h-10">
                                        {{ $claim->description }}
                                    </p>

                                    <div class="pt-4 border-t border-gray-50 flex flex-col gap-3 mt-auto">
                                        <div class="flex justify-between items-center">
                                            <div class="text-sm font-bold text-gray-700">
                                                {{ $claim->amount ? '$'.number_format($claim->amount, 2) : 'No Amount' }}
                                            </div>
                                            
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-blue-100 text-blue-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$claim->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($claim->status) }}
                                            </span>
                                        </div>
                                        <a href="{{ route($routePrefix . '.claims.show', $claim) }}" class="text-center text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-2 rounded transition">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-12 text-center rounded-lg shadow-sm border border-gray-100">
                                <div class="mb-4 flex justify-center text-gray-300">
                                    <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No claims submitted</h3>
                                <p class="text-gray-500">You haven't submitted any claims yet.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $claims->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
