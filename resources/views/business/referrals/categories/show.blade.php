<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Referral Campaign Details') }}
            </h2>
            <a href="{{ route('business.referrals.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Campaigns</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Campaign Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col md:flex-row gap-6">
                    @if($category->image_path)
                        <div class="w-full md:w-1/4">
                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full rounded-lg shadow-sm border border-gray-100">
                        </div>
                    @endif

                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Created on {{ $category->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-amber-600 font-bold text-lg">{{ number_format($category->points_reward) }} pts / referral</span>
                            </div>
                        </div>

                        <div class="mt-4 prose text-gray-600">
                            {{ $category->description }}
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Referral Link</label>
                            <div class="mt-1 flex items-center gap-2">
                                <a href="{{ $category->referral_link }}" target="_blank" class="text-indigo-600 hover:underline truncate">
                                    {{ $category->referral_link }}
                                </a>
                                <button onclick="navigator.clipboard.writeText('{{ $category->referral_link }}'); alert('Link copied!');" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </div>
                        </div>

                         <div class="mt-6 flex gap-3">
                            <a href="{{ route('business.referrals.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Edit Campaign
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referrals List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Submitted Referrals</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referrer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referred Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted On</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($referrals as $referral)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $referral->referrer->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $referral->referrer->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $referral->referred_email }}</div>
                                        @if($referral->notes)
                                            <div class="text-xs text-gray-500 italic mt-1">"{{ Str::limit($referral->notes, 30) }}"</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $referral->created_at->format('M d, Y') }}
                                        <span class="text-xs block text-gray-400">{{ $referral->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($referral->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($referral->status === 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($referral->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('business.referrals.approve', $referral) }}" method="POST" onsubmit="return confirm('Approve this referral? Points will be deducted from your balance.');">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-bold">Approve</button>
                                                </form>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('business.referrals.reject', $referral) }}" method="POST" onsubmit="return confirm('Reject this referral?');">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        No referrals submitted for this campaign yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $referrals->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
