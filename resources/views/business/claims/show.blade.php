<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Claim Review') }} #{{ $claim->id }}
            </h2>
            <a href="{{ route('business.claims.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-900">&lsaquo; Back to List</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Info Column -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Claim Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Title</label>
                                <p class="text-lg font-medium text-gray-900">{{ $claim->title }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Category</label>
                                <p class="text-lg font-medium text-gray-900">{{ $claim->category->name ?? 'Uncategorized' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                                <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap">{{ $claim->description }}</p>
                            </div>
                            @if($claim->amount)
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Claimed Amount</label>
                                    <p class="text-lg font-bold text-indigo-600">${{ number_format($claim->amount, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($claim->document_path)
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold mb-4 border-b pb-2">Supporting Document</h3>
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <svg class="h-10 w-10 text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">View Attachment</p>
                                    <p class="text-xs text-gray-500">The customer uploaded a document for verification.</p>
                                </div>
                                <a href="{{ Storage::url($claim->document_path) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 rounded shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Open Document
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Column -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Status & Actions</h3>
                        
                        <div class="mb-6">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Current Status</label>
                            <div class="mt-1">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-blue-100 text-blue-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$claim->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($claim->status) }}
                                </span>
                            </div>
                        </div>

                        @if($claim->status === 'pending')
                            <form method="POST" action="{{ route('business.claims.update', $claim->id) }}" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <x-input-label for="reward_points" :value="__('Reward Points (Deducted from your balance)')" />
                                    <x-text-input id="reward_points" name="reward_points" type="number" class="mt-1 block w-full text-sm" value="{{ $claim->category ? $claim->category->points_reward : round($claim->amount * 0.01) }}" />
                                    <p class="mt-1 text-xs text-indigo-600 font-medium">Your current balance: {{ number_format(auth()->user()->points) }} pts</p>
                                </div>

                                <div>
                                    <x-input-label for="admin_notes" :value="__('Admin Notes (Optional)')" />
                                    <textarea id="admin_notes" name="admin_notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Reason for approval/rejection...">{{ $claim->admin_notes }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 gap-2">
                                    <button type="submit" name="status" value="approved" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                        Approve & Reward
                                    </button>
                                    <button type="submit" name="status" value="rejected" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition text-center">
                                        Reject Claim
                                    </button>
                                </div>
                            </form>
                        @else
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Admin Notes</label>
                                <p class="text-sm text-gray-700 mt-1 italic italic">{{ $claim->admin_notes ?: 'No notes provided.' }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100">
                        <h3 class="text-sm font-bold text-indigo-900 mb-2 uppercase tracking-tight">Customer Info</h3>
                        <div class="text-sm text-indigo-800">
                            <p class="font-bold">{{ $claim->user->name }}</p>
                            <p>{{ $claim->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
