<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Referral Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @forelse($categories as $category)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    {{-- Category Header --}}
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-4">
                            @if($category->image_path)
                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-12 h-12 rounded-lg object-cover shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
                                <div class="flex items-center gap-3 mt-0.5">
                                    <span class="text-xs text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded">
                                        {{ number_format($category->points_reward) }} pts / referral
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $category->participants_count }} {{ Str::plural('participant', $category->participants_count) }}
                                    </span>
                                    @if($category->is_active)
                                        <span class="text-xs text-green-700 font-semibold bg-green-50 px-2 py-0.5 rounded">Active</span>
                                    @else
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('business.referrals.categories.edit', $category) }}"
                           class="text-xs text-gray-500 hover:text-indigo-600 transition shrink-0">Edit Campaign →</a>
                    </div>

                    {{-- Participants Table --}}
                    @if($category->participants->isEmpty())
                        <div class="p-8 text-center text-gray-400">
                            <svg class="h-10 w-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm font-medium">No one has joined this campaign yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Clicks</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Link Clicks Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($category->participants as $participant)
                                        <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $category->id }}-{{ $participant->id }}">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $participant->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $participant->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $participant->pivot->created_at ? $participant->pivot->created_at->format('M d, Y') : '—' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-semibold
                                                    {{ $participant->visits->count() > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                                                    {{ $participant->visits->count() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                @if($participant->visits->count() > 0)
                                                    <button
                                                        onclick="toggleDropdown('visits-{{ $category->id }}-{{ $participant->id }}')"
                                                        class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                                                        <span>View Clicks</span>
                                                        <svg id="chevron-visits-{{ $category->id }}-{{ $participant->id }}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400">No clicks yet</span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Expandable Visits Dropdown --}}
                                        @if($participant->visits->count() > 0)
                                            <tr id="visits-{{ $category->id }}-{{ $participant->id }}" class="hidden">
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
                    @endif
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center text-gray-400">
                    <svg class="h-12 w-12 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-lg font-semibold text-gray-500 mb-1">No referral campaigns yet.</p>
                    <p class="text-sm">Create a referral category to get started.</p>
                    <a href="{{ route('business.referrals.categories.create') }}"
                       class="inline-block mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md transition">
                        Create Campaign
                    </a>
                </div>
            @endforelse

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
