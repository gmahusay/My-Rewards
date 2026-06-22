<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($category) ? __('Manage Customer Claims - ') . $category->name : __('Manage Customer Claims') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Claims</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($metrics['total_claims']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Amount Claims</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">${{ number_format($metrics['total_amount'], 2) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-amber-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Points Rewarded</div>
                        <div class="mt-1 text-3xl font-bold text-amber-600">{{ number_format($metrics['total_points']) }} pts</div>
                    </div>
                </div>
            </div>

            <div class="bg-none overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($claims as $claim)
                            <div class="group relative bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                                <!-- Card Image -->
                                @if($claim->category && $claim->category->image_path)
                                    <div class="h-40 w-full overflow-hidden">
                                        <img src="{{ Storage::url($claim->category->image_path) }}" alt="{{ $claim->category->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                        <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                @endif

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-gray-100 rounded-full mr-2">
                                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            </div>
                                            <div class="text-sm">
                                                <div class="font-bold text-gray-900">{{ $claim->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $claim->user->email }}</div>
                                            </div>
                                        </div>
                                        @if($claim->rewarded_points > 0)
                                            <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                                {{ number_format($claim->rewarded_points) }} pts
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition truncate" title="{{ $claim->title }}">{{ $claim->title }}</h3>
                                    
                                    <div class="flex items-center text-sm text-gray-500 mb-3">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        {{ $claim->created_at->format('M d, Y') }}
                                    </div>

                                    <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                                        <div>
                                            <span class="font-semibold">{{ $claim->amount ? '$'.number_format($claim->amount, 2) : 'No Amount' }}</span>
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

                                    <div class="pt-4 border-t border-gray-50 mt-auto">
                                        <a href="{{ route('business.claims.show', $claim->id) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                                            Review Claim
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-12 text-center rounded-lg shadow-sm border border-gray-100">
                                <div class="mb-4 flex justify-center text-gray-300">
                                    <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No claims found</h3>
                                <p class="text-gray-500">There are no claims to display at this time.</p>
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
