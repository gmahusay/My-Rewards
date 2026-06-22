<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Events Management') }}
            </h2>
            <a href="{{ route('business.events.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                + Create New Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Events -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="h-12 w-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Total Events</span>
                        <span class="text-2xl font-bold text-gray-900">{{ number_format($metrics['total_events']) }}</span>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="h-12 w-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Upcoming Events</span>
                        <span class="text-2xl font-bold text-gray-900">{{ number_format($metrics['upcoming_events']) }}</span>
                    </div>
                </div>

                <!-- Total Points Awarded -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="h-12 w-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Points Awarded</span>
                        <span class="text-2xl font-bold text-gray-900">{{ number_format($metrics['total_points_awarded']) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Image</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Reward</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($events as $event)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($event->image_path)
                                                <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="h-10 w-10 rounded-full object-cover border border-gray-100 shadow-sm">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100">
                                                    <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002-2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('events.show', $event) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-900 transition block">
                                                {{ $event->title }}
                                            </a>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ $event->location }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}<br>
                                            <span class="text-xs text-indigo-500 font-semibold">{{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-200">
                                                {{ number_format($event->points_reward) }} pts
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($event->is_active && $event->event_date->isFuture())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Upcoming
                                                </span>
                                            @elseif($event->event_date->isPast())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('business.events.participants', $event) }}" class="text-green-600 hover:text-green-900">Participants</a>
                                                <a href="{{ route('business.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('business.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                            No events created yet. Start by creating one!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
