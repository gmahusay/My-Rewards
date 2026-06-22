<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gamification Campaigns') }}
            </h2>
            <a href="{{ route('business.gamification.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                + Create Campaign
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Metrics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500">Total Campaigns</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $metrics['total'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500">Active Campaigns</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $metrics['active'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500">
                    <div class="text-sm font-medium text-gray-500">Total Participants</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($metrics['total_joins']) }}</div>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Targets</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward (XP)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($campaigns as $campaign)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($campaign->logo_path)
                                                    <img src="{{ Storage::url($campaign->logo_path) }}" alt="Logo" class="h-10 w-10 rounded-full object-cover mr-3 border">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center mr-3 border">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('business.gamification.show', $campaign) }}"
                                                       class="text-sm font-bold text-gray-900 hover:text-indigo-600">
                                                        {{ $campaign->title }}
                                                    </a>
                                                    @if($campaign->description)
                                                        <p class="text-xs text-gray-400 mt-0.5 max-w-xs truncate">{{ $campaign->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($campaign->targets as $target)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $target->type_label }}: {{ $target->target_value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-amber-600 font-bold whitespace-nowrap">
                                            {{ number_format($campaign->reward_points) }} XP
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            {{ number_format($campaign->participants_count) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php $status = $campaign->status_label; @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $status === 'Active' ? 'bg-green-100 text-green-800' : ($status === 'Expired' ? 'bg-red-100 text-red-800' : ($status === 'Upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('business.gamification.show', $campaign) }}"
                                                   class="text-gray-500 hover:text-gray-700">View</a>
                                                <a href="{{ route('business.gamification.edit', $campaign) }}"
                                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('business.gamification.destroy', $campaign) }}" method="POST"
                                                      onsubmit="return confirm('Delete this campaign?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 text-sm">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <p>No campaigns created yet. <a href="{{ route('business.gamification.create') }}" class="text-indigo-600 hover:underline">Create your first one!</a></p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $campaigns->links() }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
