<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upcoming Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="group relative bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                        <a href="{{ route('events.show', $event) }}" class="absolute inset-0 z-10" aria-label="View Event Details"></a>
                        
                        @if($event->image_path)
                            <div class="h-40 w-full overflow-hidden">
                                <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                    {{ number_format($event->points_reward) }} pts
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                            
                            @php 
                                $participation = auth()->user()->joinedEvents()->where('event_id', $event->id)->first();
                            @endphp

                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9l-1.172-1.172a4 4 0 115.657-5.657L17.657 16.657z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                {{ $event->location ?? 'To be announced' }}
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-2 mb-4 h-10">
                                {{ $event->description }}
                            </p>

                            <div class="pt-4 border-t border-gray-50 flex justify-between items-center mt-auto">
                                <div class="text-sm font-semibold text-indigo-600">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }} at {{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}
                                </div>
                                
                                @if($participation)
                                    @php
                                        $statusConfig = [
                                            'going' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-100', 'label' => 'Going'],
                                            'maybe' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'label' => 'Maybe'],
                                            'not_going' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-100', 'label' => 'Not Going'],
                                        ];
                                        $conf = $statusConfig[$participation->pivot->status] ?? $statusConfig['going'];
                                    @endphp
                                    <span class="px-2 py-0.5 {{ $conf['bg'] }} {{ $conf['text'] }} rounded-full text-[10px] font-bold border {{ $conf['border'] }} relative z-20">
                                        {{ $conf['label'] }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 group-hover:text-indigo-500 transition">View Details</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded-lg shadow-sm">
                        <div class="mb-4 flex justify-center text-gray-300">
                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM15 4V8h4M8 12h8M8 16h5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No upcoming events</h3>
                        <p class="text-gray-500">Check back later for exciting ceremonies and activities!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
