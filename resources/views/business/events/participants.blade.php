<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event Participants: ') }} {{ $event->title }}
            </h2>
            <a href="{{ route('business.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Events</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-indigo-900 font-bold">{{ $event->title }}</h3>
                            <p class="text-sm text-indigo-700 italic">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-bold uppercase tracking-wider text-indigo-500">Event Reward</span>
                            <span class="text-xl font-bold text-indigo-900">{{ number_format($event->points_reward) }} pts</span>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left: Participants Table -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                Recent Participants ({{ $participants->count() }})
                            </h3>
                            <div class="overflow-hidden border border-gray-100 rounded-xl">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 text-[10px] uppercase tracking-wider font-bold text-gray-500">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left">User</th>
                                            <th scope="col" class="px-6 py-3 text-left">RSVP</th>
                                            <th scope="col" class="px-6 py-3 text-left">Status</th>
                                            <th scope="col" class="px-6 py-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($participants as $participant)
                                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold mr-3 border border-indigo-100/50 text-xs">
                                                            {{ substr($participant->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-bold text-gray-900 leading-none">{{ $participant->name }}</div>
                                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $participant->role }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusConfig = [
                                                            'going' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'label' => 'Going'],
                                                            'maybe' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'Maybe'],
                                                            'not_going' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Not Going'],
                                                        ];
                                                        $conf = $statusConfig[$participant->pivot->status] ?? $statusConfig['going'];
                                                    @endphp
                                                    <span class="px-2 py-0.5 inline-flex text-[10px] leading-5 font-bold rounded-full {{ $conf['bg'] }} {{ $conf['text'] }} border">
                                                        {{ $conf['label'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($participant->pivot->points_awarded)
                                                        <span class="px-2 py-0.5 inline-flex text-[10px] leading-5 font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                            Awarded
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 inline-flex text-[10px] leading-5 font-bold rounded-full bg-gray-50 text-gray-500 border border-gray-100">
                                                            Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    @if(!$participant->pivot->points_awarded)
                                                        <form action="{{ route('business.events.attendance.record', $event) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="user_id" value="{{ $participant->id }}">
                                                            <button type="submit" onclick="return confirm('Award {{ $event->points_reward }} points to this user?');" class="text-xs font-bold text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition">
                                                                Award Points
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-[10px] text-gray-400 italic">
                                                            {{ \Carbon\Carbon::parse($participant->pivot->awarded_at)->format('M d, h:i A') }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 text-sm italic">
                                                    No app participants yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right: Manual Selection -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 sticky top-4">
                                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6 font-bold">Record Physical Attendance</h3>
                                <p class="text-xs text-gray-500 mb-6 leading-relaxed">Select an employee or customer who is physically at the event to award them points, even if they didn't join via the app.</p>
                                
                                <form action="{{ route('business.events.attendance.record', $event) }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="user_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Select Person</label>
                                            <select name="user_id" id="user_id" class="w-full border-gray-200 rounded-xl text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                <option value="">-- Choose User --</option>
                                                <optgroup label="Employees">
                                                    @foreach($eligibleUsers->where('role', 'employee') as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="Customers">
                                                    @foreach($eligibleUsers->where('role', 'customer') as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>

                                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform active:scale-95">
                                            Confirm & Award Points
                                        </button>
                                        <p class="text-[10px] text-center text-gray-400 font-medium">This will award +{{ $event->points_reward }} points.</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
