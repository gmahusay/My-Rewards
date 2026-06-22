<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $campaign->title }}
            </h2>
            <a href="{{ route('gamification.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← All Campaigns</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded">{{ session('status') }}</div>
            @endif

            {{-- Campaign Header --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl p-6 text-white shadow-md">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div class="flex items-center gap-5">
                        @if($campaign->logo_path)
                            <img src="{{ Storage::url($campaign->logo_path) }}" alt="Logo" class="h-20 w-20 rounded-xl object-cover bg-white p-1 shadow-sm shrink-0">
                        @else
                            <div class="h-20 w-20 rounded-xl bg-white/20 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold">{{ $campaign->title }}</h3>
                            @if($campaign->description)
                                <p class="mt-1 text-indigo-200 text-sm">{{ $campaign->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-indigo-200 text-xs uppercase tracking-wide">Completion Reward</p>
                        <p class="text-3xl font-extrabold text-yellow-300">⭐ {{ number_format($campaign->reward_points) }} XP</p>
                    </div>
                </div>
                @if($campaign->end_date)
                    <p class="mt-4 text-indigo-200 text-sm">
                        Ends on {{ $campaign->end_date->format('F d, Y') }}
                        @if($campaign->end_date->isFuture())
                            ({{ $campaign->end_date->diffForHumans() }})
                        @endif
                    </p>
                @endif
            </div>

            {{-- Not Joined Yet --}}
            @if(!$participant)
                <div class="bg-white shadow-sm sm:rounded-xl p-8 text-center">
                    <p class="text-gray-600 mb-4">You haven't joined this campaign yet.</p>
                    @if($campaign->status_label === 'Active')
                        <form action="{{ route('gamification.join', $campaign) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                                Join Campaign Now
                            </button>
                        </form>
                    @else
                        <p class="text-gray-400 text-sm">This campaign is {{ strtolower($campaign->status_label) }} and cannot be joined.</p>
                    @endif
                </div>
            @else
                {{-- Progress Section --}}
                @php
                    $totalTargets = $campaign->targets->count();
                    $completedTargets = $participant->progress->where('is_completed', true)->count();
                    $overallPct = $totalTargets > 0 ? (int)(($completedTargets / $totalTargets) * 100) : 0;
                @endphp

                <div class="bg-white shadow-sm sm:rounded-xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Your Level Progress</h3>
                        @if($participant->is_completed)
                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">🎉 Completed!</span>
                        @else
                            <span class="text-sm text-gray-500">{{ $completedTargets }}/{{ $totalTargets }} levels done</span>
                        @endif
                    </div>

                    {{-- Overall progress bar --}}
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $overallPct }}%"></div>
                    </div>

                    {{-- Per-Level Progress (sequential) --}}
                    <div class="space-y-4">
                        @php $sortedTargets = $campaign->targets->sortBy('level'); @endphp
                        @foreach($sortedTargets as $loopIndex => $target)
                            @php
                                $prog = $participant->progress->firstWhere('target_id', $target->id);
                                $current = $prog ? $prog->current_value : 0;
                                $pct = $target->target_value > 0 ? min(100, (int)(($current / $target->target_value) * 100)) : 0;
                                $done = $prog && $prog->is_completed;
                                // Is this level locked? (previous level not yet done)
                                $prevTarget = $loopIndex > 0 ? $sortedTargets->values()->get($loopIndex - 1) : null;
                                $prevProg = $prevTarget ? $participant->progress->firstWhere('target_id', $prevTarget->id) : null;
                                $locked = $prevTarget && (!$prevProg || !$prevProg->is_completed);
                            @endphp
                            <div class="flex items-start gap-4 p-4 rounded-xl border {{ $done ? 'bg-green-50 border-green-200' : ($locked ? 'bg-gray-50 border-gray-200 opacity-60' : 'bg-indigo-50 border-indigo-100') }}">
                                {{-- Level badge --}}
                                <div class="shrink-0 flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-full font-bold text-sm
                                        {{ $done ? 'bg-green-500 text-white' : ($locked ? 'bg-gray-300 text-gray-500' : 'bg-indigo-600 text-white') }}">
                                        @if($done)
                                            <i class="fa-solid fa-check"></i>
                                        @elseif($locked)
                                            <i class="fa-solid fa-lock"></i>
                                        @else
                                            {{ $target->level }}
                                        @endif
                                    </span>
                                    <span class="text-xs font-semibold {{ $done ? 'text-green-600' : ($locked ? 'text-gray-400' : 'text-indigo-600') }}">Lvl {{ $target->level }}</span>
                                </div>

                                {{-- Level info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($target->icon)
                                            <i class="{{ $target->icon }} {{ $done ? 'text-green-500' : ($locked ? 'text-gray-400' : 'text-indigo-500') }}"></i>
                                        @endif
                                        <span class="font-semibold text-gray-800 text-sm">{{ $target->display_label }}</span>
                                        @if($done)
                                            <span class="text-green-600 text-xs font-bold ml-auto">✓ Completed</span>
                                        @elseif($locked)
                                            <span class="text-gray-400 text-xs ml-auto">Complete previous level first</span>
                                        @endif
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                        <div class="{{ $done ? 'bg-green-500' : 'bg-indigo-500' }} h-2 rounded-full transition-all duration-500"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ number_format($current) }} / {{ number_format($target->target_value) }} &mdash; {{ $pct }}% complete</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($participant->is_completed && $participant->completed_at)
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                            <p class="text-green-800 font-semibold">🎉 You completed this campaign on {{ $participant->completed_at->format('M d, Y') }} and earned <strong>{{ number_format($campaign->reward_points) }} XP</strong>!</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
