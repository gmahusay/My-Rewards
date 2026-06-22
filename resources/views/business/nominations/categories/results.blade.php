<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nomination Results: ') }} {{ $category->name }}
            </h2>
            <a href="{{ route('business.nominations.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to List</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8 p-4 bg-indigo-50 rounded-lg border border-indigo-100 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-indigo-800 italic">Winners earn: <strong class="not-italic text-amber-600 font-bold text-lg">{{ number_format($category->points_reward) }} points</strong></p>
                            <p class="text-xs text-indigo-600 mt-1">Period: {{ $category->start_date->format('M d') }} - {{ $category->end_date->format('M d, Y') }}</p>
                        </div>
                        @if($category->winner_id)
                            <div class="text-right">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">Awarded to {{ $category->winner->name }}</span>
                                <p class="text-xs text-gray-500 mt-1">on {{ $category->awarded_at->format('M d, Y') }}</p>
                            </div>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Nomination Leaderboard</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Votes</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Action</th>
                                </tr>
                            </thead>
                                @forelse($results as $nomineeId => $nominations)
                                    @php
                                        $nominee = $nominations->first()->nominee;
                                        $count = $nominations->count();
                                    @endphp
                                    <tbody x-data="{ open: false }" class="bg-white divide-y divide-gray-200">
                                        <tr class="{{ $category->winner_id == $nomineeId ? 'bg-green-50' : '' }} hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3 cursor-pointer" @click="open = !open">
                                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                        <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </button>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $nominee->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $nominee->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-indigo-100 text-indigo-800">
                                                    {{ $count }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if(!$category->winner_id)
                                                    <form action="{{ route('business.nominations.categories.award', $category) }}" method="POST" onsubmit="return confirm('Award {{ number_format($category->points_reward) }} points to {{ $nominee->name }}? This action cannot be undone.');">
                                                        @csrf
                                                        <input type="hidden" name="winner_id" value="{{ $nomineeId }}">
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition">
                                                            Award Winner
                                                        </button>
                                                    </form>
                                                @elseif($category->winner_id == $nomineeId)
                                                    <span class="text-green-600 font-bold flex items-center justify-end">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Winner
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- Expandable Details Row --}}
                                        <tr x-show="open" x-transition.opacity class="bg-gray-50 border-t-0" style="display: none;">
                                            <td colspan="3" class="px-6 py-4">
                                                <div class="text-sm text-gray-700">
                                                    <h4 class="font-semibold text-indigo-700 mb-2">Nominations Details for {{ $nominee->name }}</h4>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        @foreach($nominations as $nomination)
                                                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                                    <span class="font-medium text-gray-900">{{ $nomination->nominator->name }}</span>
                                                                    <span class="text-xs text-gray-500 ml-auto">{{ $nomination->created_at->format('M d, Y g:i A') }}</span>
                                                                </div>
                                                                <p class="text-gray-600 italic">"{{ $nomination->reason }}"</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                @empty
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                                No nominations have been submitted for this category yet.
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
