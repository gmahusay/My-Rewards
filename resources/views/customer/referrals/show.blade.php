<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }}
            </h2>
            <a href="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.index' : 'customer.referrals.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Campaigns</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                            @if($hasJoined)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Joined
                                </span>
                            @endif
                        </div>

                        <div class="prose text-gray-600 mb-6">
                            {{ $category->description }}
                        </div>

                        <!-- Referral Link — only visible after joining -->
                        @if($hasJoined)
                            @php
                                $userToken = auth()->user()->referral_identifier ?? null;
                                $publicUrl = $category->referral_link && $userToken ? route('referrals.public', [$category->referral_link, $userToken]) : null;
                            @endphp
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Your Referral Link</label>
                                <div class="flex items-center space-x-2">
                                    @if($publicUrl)
                                        <input type="text" readonly value="{{ $publicUrl }}" class="text-sm text-gray-500 bg-white border border-gray-300 rounded focus:ring-0 w-full p-2">
                                        <button onclick="navigator.clipboard.writeText('{{ $publicUrl }}'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000);"
                                                class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded font-bold text-sm whitespace-nowrap">
                                            Copy
                                        </button>
                                    @else
                                        <p class="text-sm text-gray-400 italic">Referral link unavailable.</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Join CTA -->
                            <div class="p-6 bg-indigo-50 border border-indigo-100 rounded-lg text-center">
                                <svg class="h-10 w-10 mx-auto text-indigo-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <p class="text-sm font-semibold text-indigo-800 mb-1">Join this campaign to get your referral link</p>
                                <p class="text-xs text-indigo-600 mb-4">You need to join before you can share your link or submit referrals.</p>
                                <form action="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.join' : 'customer.referrals.join', $category) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-md transition">
                                        Join Campaign
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Refer a Friend Form — only visible after joining -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Refer a Friend</h3>

                        @if($hasJoined)
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
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-center text-gray-400">
                                <svg class="h-10 w-10 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <p class="text-sm font-medium">Join the campaign first to submit referrals.</p>
                            </div>
                        @endif
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
                                        @if($hasJoined)
                                            You haven't made any referrals for this campaign yet.
                                        @else
                                            Join the campaign to start making referrals.
                                        @endif
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

