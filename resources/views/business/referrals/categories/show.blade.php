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

            <!-- Campaign Participants List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Joined Participants</h3>
                </div>
                
                @if($participants->isEmpty())
                    <div class="p-8 text-center text-gray-400">
                        <svg class="h-10 w-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-sm font-medium">No one has joined this campaign yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined On</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Clicks</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($participants as $participant)
                                    <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $participant->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $participant->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $participant->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $participant->pivot->created_at ? $participant->pivot->created_at->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-semibold
                                                {{ $participant->visits->count() > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $participant->visits->count() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($participant->visits->count() > 0)
                                                <button
                                                    onclick="toggleDropdown('visits-{{ $participant->id }}')"
                                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 font-bold transition">
                                                    <span>View Clicks</span>
                                                    <svg id="chevron-visits-{{ $participant->id }}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400">No clicks yet</span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Expandable Link Clicks Detail --}}
                                    @if($participant->visits->count() > 0)
                                        <tr id="visits-{{ $participant->id }}" class="hidden">
                                            <td colspan="4" class="px-6 py-0">
                                                <div class="py-4 pl-4 border-l-4 border-indigo-200 ml-2">
                                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                                                        Link Clicks by {{ $participant->name }}
                                                    </p>
                                                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                                                        <table class="min-w-full text-sm">
                                                            <thead class="bg-gray-50">
                                                                <tr>
                                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Referral Source / Domain</th>
                                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full URL</th>
                                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-100 bg-white">
                                                                @foreach($participant->visits as $vi => $visit)
                                                                    <tr class="hover:bg-indigo-50 transition-colors">
                                                                        <td class="px-4 py-2 text-gray-400 text-xs">{{ $vi + 1 }}</td>
                                                                        <td class="px-4 py-2">
                                                                            @if($visit->referer_url)
                                                                                <span class="font-medium text-gray-800">{{ $visit->referer_domain }}</span>
                                                                            @else
                                                                                <span class="text-gray-400 italic">Direct / Unknown</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="px-4 py-2 max-w-xs">
                                                                            @if($visit->referer_url)
                                                                                <span class="text-xs text-gray-400 truncate block" title="{{ $visit->referer_url }}">
                                                                                    {{ $visit->referer_url }}
                                                                                </span>
                                                                            @else
                                                                                <span class="text-gray-300 text-xs">—</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="px-4 py-2 font-mono text-xs text-gray-500">{{ $visit->ip ?? '—' }}</td>
                                                                        <td class="px-4 py-2 text-xs text-gray-500 whitespace-nowrap">
                                                                            {{ $visit->created_at->format('M d, Y') }}
                                                                            <span class="text-gray-400 ml-1">{{ $visit->created_at->format('H:i') }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200">
                        {{ $participants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(id) {
            const row = document.getElementById(id);
            const chevron = document.getElementById('chevron-' + id);
            if (row) {
                row.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-180');
            }
        }
    </script>
</x-app-layout>
