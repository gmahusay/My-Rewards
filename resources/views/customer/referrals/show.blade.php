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

            {{-- Campaign Info + Referral Link --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start gap-6">

                        {{-- Image --}}
                        @if($category->image_path)
                            <div class="shrink-0 w-full md:w-48 h-36 overflow-hidden rounded-lg">
                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="shrink-0 w-full md:w-48 h-36 bg-indigo-50 flex items-center justify-center rounded-lg">
                                <svg class="h-12 w-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        @endif

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $category->name }}</h3>
                                @if($hasJoined)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Joined
                                    </span>
                                @endif
                            </div>

                            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-bold mb-3">
                                {{ number_format($category->points_reward) }} pts / referral
                            </span>

                            <p class="text-sm text-gray-600 mb-5">{{ $category->description }}</p>

                            {{-- Referral Link --}}
                            @if($hasJoined)
                                @php
                                    $userToken = auth()->user()->referral_identifier ?? null;
                                    $publicUrl = $category->referral_link && $userToken
                                        ? route('referrals.public', [$category->referral_link, $userToken])
                                        : null;
                                @endphp
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Your Referral Link</p>
                                    <div class="flex items-center gap-2">
                                        @if($publicUrl)
                                            <input type="text" readonly id="referral-link-input" value="{{ $publicUrl }}"
                                                   class="flex-1 text-sm text-gray-600 bg-white border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                            <button id="copy-btn" type="button"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-semibold text-sm transition whitespace-nowrap">
                                                Copy
                                            </button>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Referral link not available.</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="p-5 bg-indigo-50 border border-indigo-100 rounded-lg text-center">
                                    <svg class="h-8 w-8 mx-auto text-indigo-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-indigo-800 mb-1">Join to get your referral link</p>
                                    <p class="text-xs text-indigo-600 mb-3">You need to join before you can share your link.</p>
                                    <form action="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.join' : 'customer.referrals.join', $category) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-md transition">
                                            Join Campaign
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Link Clicks Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">People Who Clicked Your Link</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Every time someone opens your referral link, it appears here.</p>
                    </div>
                    @if($hasJoined)
                        <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold">
                            {{ $myVisits->count() }} {{ Str::plural('click', $myVisits->count()) }}
                        </span>
                    @endif
                </div>

                @if($hasJoined)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Source / Domain</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($myVisits as $i => $visit)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-400">{{ $i + 1 }}</td>
                                        <td class="px-6 py-4">
                                            @if($visit->referer_url)
                                                <div class="text-sm font-medium text-gray-900">{{ $visit->referer_domain }}</div>
                                                <div class="text-xs text-gray-400 truncate max-w-xs" title="{{ $visit->referer_url }}">{{ $visit->referer_url }}</div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">Direct / Unknown</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $visit->ip ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $visit->created_at->format('M d, Y') }}
                                            <span class="text-gray-400 text-xs ml-1">{{ $visit->created_at->format('H:i') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                            <svg class="h-10 w-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <p class="text-sm font-medium">No one has clicked your link yet.</p>
                                            <p class="text-xs mt-1">Share your referral link to start tracking clicks!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center text-gray-400">
                        <p class="text-sm">Join the campaign to start tracking your referral link clicks.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @if($hasJoined)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('copy-btn');
            var input = document.getElementById('referral-link-input');

            if (!btn || !input) return;

            btn.addEventListener('click', function () {
                var url = input.value;

                // Try modern clipboard API first
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function () {
                        showCopied(btn);
                    }).catch(function () {
                        fallbackCopy(input, btn);
                    });
                } else {
                    fallbackCopy(input, btn);
                }
            });

            function fallbackCopy(input, btn) {
                input.select();
                input.setSelectionRange(0, 99999);
                try {
                    document.execCommand('copy');
                    showCopied(btn);
                } catch (e) {
                    alert('Could not copy automatically. Please copy the link manually.');
                }
                window.getSelection && window.getSelection().removeAllRanges();
            }

            function showCopied(btn) {
                var original = btn.textContent;
                btn.textContent = 'Copied!';
                btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-green-600');
                setTimeout(function () {
                    btn.textContent = original;
                    btn.classList.remove('bg-green-600');
                    btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                }, 2000);
            }
        });
    </script>
    @endif
</x-app-layout>
