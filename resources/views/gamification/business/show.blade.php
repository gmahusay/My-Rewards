<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $campaign->title }}</h2>
            <div class="flex gap-3">
                <a href="{{ route('business.gamification.edit', $campaign) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Edit Campaign
                </a>
                <a href="{{ route('business.gamification.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Campaign Info --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Status</p>
                        @php $status = $campaign->status_label; @endphp
                        <span class="mt-1 inline-flex px-2.5 py-0.5 rounded-full text-sm font-semibold
                            {{ $status === 'Active' ? 'bg-green-100 text-green-800' : ($status === 'Expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700') }}">
                            {{ $status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">XP Reward</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ number_format($campaign->reward_points) }} XP</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Duration</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'Open' }}
                            →
                            {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'No End' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Participants</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $campaign->participants->count() }}</p>
                    </div>
                </div>

                @if($campaign->description)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">{{ $campaign->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Levels --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Campaign Levels</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($campaign->targets->sortBy('level') as $target)
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-indigo-600 text-white font-bold text-xs">{{ $target->level }}</span>
                                @if($target->icon)
                                    <i class="{{ $target->icon }} text-indigo-500 text-base"></i>
                                @endif
                            </div>
                            <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">{{ $target->type_label }}</p>
                            <p class="mt-1 text-sm text-gray-700">{{ $target->display_label }}</p>
                            <p class="mt-2 text-2xl font-bold text-indigo-900">{{ number_format($target->target_value) }}<span class="text-sm font-normal text-gray-500 ml-1">required</span></p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Participants --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Participants ({{ $campaign->participants->count() }})</h3>
                @if($campaign->participants->isEmpty())
                    <p class="text-sm text-gray-500">No participants yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Participant</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Current Level</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Level Progress</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Status</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase text-xs">Joined At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($campaign->participants as $participant)
                                    @php
                                        $sortedTargets = $campaign->targets->sortBy('level');
                                        // Current level = lowest uncompleted target
                                        $currentTarget = null;
                                        $currentProg = null;
                                        foreach ($sortedTargets as $t) {
                                            $p = $participant->progress->firstWhere('target_id', $t->id);
                                            if (!$p || !$p->is_completed) {
                                                $currentTarget = $t;
                                                $currentProg = $p;
                                                break;
                                            }
                                        }
                                        // If all completed, show last level
                                        if (!$currentTarget && $participant->is_completed) {
                                            $currentTarget = $sortedTargets->last();
                                            $currentProg = $participant->progress->firstWhere('target_id', $currentTarget->id);
                                        }
                                        $current = $currentProg ? $currentProg->current_value : 0;
                                        $required = $currentTarget ? $currentTarget->target_value : 1;
                                        $pct = $required > 0 ? min(100, (int)(($current / $required) * 100)) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">{{ $participant->user->name }}</div>
                                            <div class="text-gray-400 text-xs">{{ $participant->user->email }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($currentTarget)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-full {{ $participant->is_completed ? 'bg-green-500' : 'bg-indigo-600' }} text-white font-bold text-xs">
                                                        @if($participant->is_completed)
                                                            <i class="fa-solid fa-check"></i>
                                                        @else
                                                            {{ $currentTarget->level }}
                                                        @endif
                                                    </span>
                                                    @if($currentTarget->icon)
                                                        <i class="{{ $currentTarget->icon }} {{ $participant->is_completed ? 'text-green-500' : 'text-indigo-500' }}"></i>
                                                    @endif
                                                    <span class="text-sm text-gray-700">{{ $currentTarget->display_label }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 min-w-[180px]">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                    <div class="{{ $participant->is_completed ? 'bg-green-500' : 'bg-indigo-500' }} h-2 rounded-full transition-all duration-300" style="width: {{ $participant->is_completed ? 100 : $pct }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600 whitespace-nowrap font-medium">
                                                    @if($participant->is_completed)
                                                        All done
                                                    @else
                                                        {{ $current }}/{{ $required }}
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($participant->is_completed)
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">✓ Done</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Lvl {{ $currentTarget->level ?? '?' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $participant->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
