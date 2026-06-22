<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-indigo-50 rounded-xl mr-4">
                                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h3>
                                        <div class="flex items-center text-sm text-gray-500 mt-1">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9l-1.172-1.172a4 4 0 115.657-5.657L17.657 16.657z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                            {{ $event->location ?? 'Location to be announced' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-4 py-2 bg-amber-50 text-amber-700 rounded-xl text-sm font-bold border border-amber-200 shadow-sm">
                                        {{ number_format($event->points_reward) }} pts
                                    </span>
                                </div>
                            </div>

                            <div class="prose max-w-none text-gray-600 mb-8 leading-relaxed">
                                {!! nl2br(e($event->description)) !!}
                            </div>

                            <!-- Reactions -->
                            <div class="flex items-center gap-2 mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                @php
                                    $reactions = [
                                        'like' => '👍',
                                        'love' => '❤️',
                                        'haha' => '😂',
                                        'wow' => '😮',
                                        'sad' => '😢',
                                        'angry' => '😡',
                                        'dislike' => '👎',
                                    ];
                                    $userReaction = $event->reactions->where('user_id', auth()->id())->first();
                                @endphp

                                @foreach($reactions as $type => $emoji)
                                    <form action="{{ route('events.react', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <button type="submit" 
                                            class="p-2 text-2xl hover:scale-125 transition-transform duration-200 focus:outline-none rounded-lg {{ $userReaction && $userReaction->type === $type ? 'bg-indigo-100 border-2 border-indigo-300' : 'hover:bg-white' }}"
                                            title="{{ ucfirst($type) }}">
                                            {{ $emoji }}
                                        </button>
                                    </form>
                                @endforeach

                                <div class="ml-auto flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-400 uppercase">Reactions</span>
                                    <div class="flex -space-x-2">
                                        @foreach($event->reactions->groupBy('type')->take(3) as $type => $group)
                                            <div class="h-6 w-6 rounded-full bg-white border border-gray-100 flex items-center justify-center text-xs shadow-sm" title="{{ count($group) }} {{ $type }}">
                                                {{ $reactions[$type] }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 ml-1">{{ $event->reactions->count() }}</span>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="grid grid-cols-2 gap-8 w-full">
                                    <div>
                                        <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Date</span>
                                        <span class="text-lg font-bold text-indigo-900">{{ \Carbon\Carbon::parse($event->event_date)->format('l, F d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Time</span>
                                        <span class="text-lg font-bold text-indigo-900">{{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participants Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="p-8">
                            <h4 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                Who's Joining ({{ $event->participants->count() }})
                            </h4>
                            
                            <div class="space-y-4">
                                @forelse($event->participants as $participant)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 transition hover:border-indigo-200">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3 shadow-inner text-xs">
                                                @if($participant->profile_photo_path)
                                                    <img src="{{ Storage::url($participant->profile_photo_path) }}" class="h-full w-full rounded-full object-cover">
                                                @else
                                                    {{ substr($participant->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $participant->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                                    {{ $participant->pivot->status }}
                                                </div>
                                            </div>
                                        </div>

                                        @if($isOwner)
                                            <div>
                                                @if(!$participant->pivot->points_awarded)
                                                    <form action="{{ route('business.events.attendance.record', $event) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $participant->id }}">
                                                        <button type="submit" onclick="return confirm('Award {{ $event->points_reward }} points to {{ $participant->name }}?');" class="text-[10px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl transition shadow-sm">
                                                            Award Points
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="flex items-center text-[10px] font-bold text-green-600 uppercase tracking-widest bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        Awarded
                                                    </span>
                                                @endif
                                            </div>
                                        @elseif($participant->pivot->points_awarded)
                                            <span class="h-2 w-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]" title="Attended & Awarded"></span>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-12 text-center text-gray-400 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                        <p class="text-sm italic">Be the first one to join!</p>
                                    </div>
                                @endforelse
                                
                                @if($isOwner && $eligibleUsers->isNotEmpty())
                                    <div class="mt-8 pt-8 border-t border-gray-100">
                                        <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Manual Point Awarding</h5>
                                        <form action="{{ route('business.events.attendance.record', $event) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <div class="flex-grow">
                                                <select name="user_id" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                    <option value="">Select person who is physically here...</option>
                                                    @foreach($eligibleUsers as $eligibleUser)
                                                        @if(!$event->participants->contains($eligibleUser->id) || !$event->participants->find($eligibleUser->id)->pivot->points_awarded)
                                                            <option value="{{ $eligibleUser->id }}">{{ $eligibleUser->name }} ({{ $eligibleUser->role }})</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-black transition shadow-lg shadow-gray-100">
                                                Award
                                            </button>
                                        </form>
                                        <p class="mt-2 text-[10px] text-gray-400 italic">Adds {{ number_format($event->points_reward) }} points to the selected user.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-8">
                            <h4 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                Discussion ({{ $event->comments->count() }})
                            </h4>

                            <!-- Comment Form -->
                            <form action="{{ route('events.comment', $event) }}" method="POST" class="mb-8 overflow-hidden rounded-2xl border-2 border-gray-50 focus-within:border-indigo-100 transition">
                                @csrf
                                <textarea name="content" rows="3" class="w-full border-none focus:ring-0 text-gray-700 placeholder-gray-400 p-4" placeholder="What are your thoughts on this event?" required></textarea>
                                <div class="bg-gray-50 p-2 flex justify-end">
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-sm">
                                        Post Comment
                                    </button>
                                </div>
                            </form>

                            <!-- Comments List -->
                            <div class="space-y-8" x-data="{ replyTo: null }">
                                @forelse($event->comments as $comment)
                                    <div class="space-y-4">
                                        <!-- Parent Comment -->
                                        <div class="flex gap-4 group">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200">
                                                @if($comment->user->profile_photo_path)
                                                    <img src="{{ Storage::url($comment->user->profile_photo_path) }}" class="h-full w-full rounded-full object-cover">
                                                @else
                                                    {{ substr($comment->user->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-sm font-bold text-gray-900">{{ $comment->user->name }}</span>
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                                                        {{ $comment->user->role }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 italic ml-auto group-hover:block hidden">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-600 leading-relaxed p-4 bg-gray-50 rounded-2xl rounded-tl-none border border-gray-100/50">
                                                    {!! nl2br(e($comment->content)) !!}
                                                </div>
                                                
                                                <!-- Comment Actions -->
                                                <div class="flex items-center gap-4 mt-2 ml-1">
                                                    <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="flex items-center gap-1 text-[11px] font-bold {{ $comment->likes->contains(auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }} transition">
                                                            <svg class="h-4 w-4" fill="{{ $comment->likes->contains(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                            <span>{{ $comment->likes->count() ?: 'Like' }}</span>
                                                        </button>
                                                    </form>
                                                    
                                                    <button @click="replyTo = (replyTo === {{ $comment->id }} ? null : {{ $comment->id }})" class="text-[11px] font-bold text-gray-400 hover:text-indigo-500 transition">
                                                        Reply
                                                    </button>

                                                    <span class="text-[10px] text-gray-300 group-hover:hidden">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>

                                                <!-- Reply Form -->
                                                <div x-show="replyTo === {{ $comment->id }}" x-cloak class="mt-4 ml-4" x-transition>
                                                    <form action="{{ route('events.comment', $event) }}" method="POST" class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                                                        @csrf
                                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                        <textarea name="content" rows="2" class="w-full border-none focus:ring-0 text-sm text-gray-700 placeholder-gray-400 px-4 py-2" placeholder="Write a reply..." required></textarea>
                                                        <div class="bg-gray-50 px-3 py-2 flex justify-end gap-2">
                                                            <button type="button" @click="replyTo = null" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 px-2 py-1">Cancel</button>
                                                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg font-bold text-[11px] hover:bg-indigo-700 transition">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nested Replies -->
                                        @if($comment->replies->count() > 0)
                                            <div class="ml-12 space-y-4 pt-2 border-l-2 border-gray-50 pl-6">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex gap-3 group">
                                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 font-bold border border-gray-100 text-[10px]">
                                                            @if($reply->user->profile_photo_path)
                                                                <img src="{{ Storage::url($reply->user->profile_photo_path) }}" class="h-full w-full rounded-full object-cover">
                                                            @else
                                                                {{ substr($reply->user->name, 0, 1) }}
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow">
                                                            <div class="flex items-center gap-2 mb-0.5">
                                                                <span class="text-[12px] font-bold text-gray-900">{{ $reply->user->name }}</span>
                                                                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100">
                                                                    {{ $reply->user->role }}
                                                                </span>
                                                            </div>
                                                            <div class="text-[13px] text-gray-600 leading-relaxed p-3 bg-gray-50 rounded-2xl rounded-tl-none border border-gray-100/30">
                                                                {!! nl2br(e($reply->content)) !!}
                                                            </div>
                                                            <div class="flex items-center gap-3 mt-1 ml-1">
                                                                <form action="{{ route('comments.like', $reply) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="flex items-center gap-1 text-[10px] font-bold {{ $reply->likes->contains(auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }} transition">
                                                                        <svg class="h-3 w-3" fill="{{ $reply->likes->contains(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                                        <span>{{ $reply->likes->count() ?: 'Like' }}</span>
                                                                    </button>
                                                                </form>
                                                                <span class="text-[9px] text-gray-300 italic">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                        <p class="text-gray-400 text-sm">No comments yet. Start the conversation!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar / Action Card -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <h4 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6 text-center">RSVP Status</h4>
                            
                            @php 
                                $participation = auth()->user()->joinedEvents()->where('event_id', $event->id)->first();
                                $currentStatus = $participation ? $participation->pivot->status : null;
                            @endphp

                            @if($event->event_date->isFuture())
                                <div class="space-y-3">
                                    <form action="{{ route('events.join', $event) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="going">
                                        <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold flex items-center justify-between transition border-2 {{ $currentStatus === 'going' ? 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-100' : 'bg-white text-gray-700 border-gray-100 hover:border-green-200' }}">
                                            <span>I'm Going</span>
                                            @if($currentStatus === 'going')
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('events.join', $event) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="maybe">
                                        <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold flex items-center justify-between transition border-2 {{ $currentStatus === 'maybe' ? 'bg-amber-500 text-white border-amber-500 shadow-lg shadow-amber-100' : 'bg-white text-gray-700 border-gray-100 hover:border-amber-200' }}">
                                            <span>Maybe</span>
                                            @if($currentStatus === 'maybe')
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('events.join', $event) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="not_going">
                                        <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold flex items-center justify-between transition border-2 {{ $currentStatus === 'not_going' ? 'bg-red-500 text-white border-red-500 shadow-lg shadow-red-100' : 'bg-white text-gray-700 border-gray-100 hover:border-red-200' }}">
                                            <span>Not Going</span>
                                            @if($currentStatus === 'not_going')
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                                
                                @if($currentStatus === 'going' || $currentStatus === 'maybe')
                                    <p class="mt-6 text-center text-xs text-gray-400 italic">Participation counts towards reward points eligibility.</p>
                                @endif
                            @else
                                <div class="py-6 text-center">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase tracking-widest border border-gray-200">Completed</span>
                                    <p class="mt-4 text-gray-400 text-sm">This event has already taken place.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow-sm rounded-xl border border-gray-100 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 mr-3">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Reward points</span>
                        </div>
                        <span class="text-lg font-bold text-indigo-600">+{{ number_format($event->points_reward) }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
