<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }}
            </h2>
            <a href="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.index' : 'customer.referrals.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Campaigns</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Campaign Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($category->image_path)
                            <div class="w-full h-48 overflow-hidden rounded-lg mb-6">
                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                             <div class="w-full h-48 bg-indigo-50 flex items-center justify-center rounded-lg mb-6">
                                <svg class="h-16 w-16 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        @endif

                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-bold uppercase tracking-wider">
                                {{ number_format($category->points_reward) }} pts / referral
                            </span>
                        </div>
                        
                        <div class="prose text-gray-600 mb-6">
                            {{ $category->description }}
                        </div>

                         <!-- Referral Link Copy -->
                        
                        @php
                            $userToken = auth()->user()->referral_identifier ?? null;
                            $publicUrl = $category->referral_link && $userToken ? route('referrals.public', [$category->referral_link, $userToken]) : null;
                        @endphp
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                             <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Your Referral Link</label>
                            <div class="flex items-center space-x-2">
                            @if($publicUrl)    
                                <input type="text" readonly value="{{ $publicUrl}}" class="text-sm text-gray-500 bg-white border border-gray-300 rounded focus:ring-0 w-full p-2">
                                <button onclick="navigator.clipboard.writeText('{{ $publicUrl }}'); alert('Link copied!');" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded font-bold text-sm">Copy</button>
                            @else 

                            @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Refer a Friend Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Refer a Friend</h3>
                        <form action="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.store' : 'customer.referrals.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            
                            <div>
                                <x-input-label for="referred_email" :value="__('Friend\'s Email Address')" />
                                <x-text-input id="referred_email" name="referred_email" type="email" class="mt-1 block w-full" required placeholder="friend@example.com" />
                                <x-input-error class="mt-2" :messages="$errors->get('referred_email')" />
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Any extra details..."></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Submit Referral') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Referral History Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">My Referrals for this Campaign</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referred Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points Awarded</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($myReferrals as $referral)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $referral->referred_email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($referral->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($referral->status === 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-600">
                                        {{ $referral->rewarded_points ? number_format($referral->rewarded_points) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $referral->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        You haven't made any referrals for this campaign yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
