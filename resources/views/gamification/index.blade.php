<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🚀 Gamification Campaigns
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded">{{ session('status') }}</div>
            @endif
            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 rounded">{{ session('info') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            @if($campaigns->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">No gamification campaigns available yet. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($campaigns as $campaign)
                        @php
                            $participant = $campaign->participants->first(); // pre-loaded for this user
                            $joined = $participant !== null;
                            $status = $campaign->status_label;
                        @endphp
                        <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col overflow-hidden hover:shadow-md transition">

                            {{-- Card Header --}}
                            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-5 text-white">
                                <div class="flex gap-4">
                                    @if($campaign->logo_path)
                                        <img src="{{ Storage::url($campaign->logo_path) }}" alt="Logo" class="h-14 w-14 rounded-lg object-cover bg-white p-0.5 shrink-0 shadow-sm">
                                    @else
                                        <div class="h-14 w-14 rounded-lg bg-white/20 flex items-center justify-center shrink-0 shadow-sm">
                                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-bold leading-snug">{{ $campaign->title }}</h3>
                                            <span class="ml-2 shrink-0 inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                                                {{ $status === 'Active' ? 'bg-green-400 text-green-900' : ($status === 'Expired' ? 'bg-red-300 text-red-900' : 'bg-yellow-300 text-yellow-900') }}">
                                                {{ $status }}
                                            </span>
                                        </div>
                                        @if($campaign->description)
                                            <p class="mt-1 text-indigo-100 text-sm line-clamp-2">{{ $campaign->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-4 text-sm">
                                    <span class="text-yellow-300 font-bold text-lg">⭐ {{ number_format($campaign->reward_points) }} XP</span>
                                    <span class="text-indigo-200">{{ $campaign->participants_count }} joined</span>
                                </div>
                            </div>

                            {{-- Levels --}}
                            <div class="p-5 flex-1">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Campaign Levels</p>
                                <ol class="space-y-2">
                                    @foreach($campaign->targets->sortBy('level') as $target)
                                        <li class="flex items-center justify-between text-sm">
                                            <span class="flex items-center gap-2 text-gray-700">
                                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs shrink-0">{{ $target->level }}</span>
                                                @if($target->icon)
                                                    <i class="{{ $target->icon }} text-indigo-500 w-4 text-center"></i>
                                                @endif
                                                {{ $target->label ?: $target->type_label }}
                                            </span>
                                            <span class="font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full text-xs">
                                                × {{ number_format($target->target_value) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>

                                @if($campaign->end_date)
                                    <p class="mt-4 text-xs text-gray-400">
                                        Ends {{ $campaign->end_date->format('M d, Y') }}
                                    </p>
                                @endif
                            </div>

                            {{-- Action --}}
                            <div class="px-5 pb-5">
                                @if($joined)
                                    <a href="{{ route('gamification.show', $campaign) }}"
                                       class="block w-full text-center px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                                        ✓ View My Progress
                                    </a>
                                @elseif($status === 'Active')
                                    <form action="{{ route('gamification.join', $campaign) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                                            Join Campaign
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full px-4 py-2.5 bg-gray-200 text-gray-500 text-sm font-semibold rounded-lg cursor-not-allowed">
                                        {{ $status }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">{{ $campaigns->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
