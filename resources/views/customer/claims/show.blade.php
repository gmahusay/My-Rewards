<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Claim Details') }} #{{ $claim->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Top Section: Status and Basic Info -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-100">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $claim->title }}</h3>
                            <div class="flex items-center text-gray-500 text-sm space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Submitted on {{ $claim->created_at->format('M d, Y') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $claim->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'approved' => 'bg-green-100 text-green-800 border-green-200',
                                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $statusClasses[$claim->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($claim->status) }}
                            </span>
                            @if($claim->rewarded_points)
                                <div class="mt-2 text-amber-600 font-bold flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    +{{ number_format($claim->rewarded_points) }} Points
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column: Details -->
                        <div class="md:col-span-2 space-y-6">
                            <!-- Details Card -->
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Claim Details</h4>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                    <div>
                                        <dt class="text-xs text-gray-500 uppercase">Amount</dt>
                                        <dd class="text-lg font-semibold text-gray-900">${{ number_format($claim->amount, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500 uppercase">Store Name</dt>
                                        <dd class="text-gray-900">{{ $claim->store_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500 uppercase">Invoice Number</dt>
                                        <dd class="font-mono text-gray-700">{{ $claim->invoice_number }}</dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs text-gray-500 uppercase mb-1">Description</dt>
                                        <dd class="text-gray-700 bg-white p-3 rounded border border-gray-200 text-sm leading-relaxed">
                                            {{ $claim->description }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Comments / Rejection Reason -->
                            @if($claim->comments)
                                <div class="bg-red-50 rounded-lg p-6 border border-red-100">
                                    <h4 class="text-sm font-bold text-red-900 uppercase tracking-wider mb-2">Feedback</h4>
                                    <p class="text-red-800">{{ $claim->comments }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column: Receipt Image -->
                        <div>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Receipt / Document</h4>
                                @if($claim->document_path)
                                    <a href="{{ Storage::url($claim->document_path) }}" target="_blank" class="block group relative overflow-hidden rounded-lg shadow-sm">
                                        @php
                                            $extension = pathinfo($claim->document_path, PATHINFO_EXTENSION);
                                        @endphp
                                        
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ Storage::url($claim->document_path) }}" alt="Receipt" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="flex flex-col items-center justify-center p-8 bg-white border border-gray-200 rounded text-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-600">View {{ strtoupper($extension) }} File</span>
                                            </div>
                                        @endif
                                        
                                        <div class="absolute inset-x-0 bottom-0 bg-black bg-opacity-50 text-white text-center text-xs py-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            Click to view full size
                                        </div>
                                    </a>
                                @else
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded border border-dashed border-gray-300 text-gray-400 text-sm">
                                        No document uploaded
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                        @php
                            $backRoute = request()->routeIs('employee.*') ? route('employee.claims.history') : route('customer.claims.history');
                            // Fallback if history route isn't best match, maybe index
                            if(!Str::contains(url()->previous(), 'history')) {
                                $backRoute = request()->routeIs('employee.*') ? route('employee.claims.index') : route('customer.claims.index');
                            }
                        @endphp
                        <a href="{{ $backRoute }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Claims
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
