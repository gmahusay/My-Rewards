<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nominations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-none overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($categories as $category)
                            <div class="group relative bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                                <!-- Card Image -->
                                <div class="relative">
                                    @if($category->image_path)
                                        <div class="h-40 w-full overflow-hidden">
                                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                            <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                        </div>
                                    @endif
                                    
                                    @if($category->end_date && $category->end_date->isPast())
                                        <div class="absolute inset-0 bg-white/80 flex items-center justify-center backdrop-blur-[1px]">
                                            <span class="px-4 py-2 bg-red-100 text-red-800 font-bold rounded-full border border-red-200 shadow-sm uppercase tracking-wider text-sm">Expired</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </div>
                                        <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                            {{ number_format($category->points_reward) }} pts
                                        </span>
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition truncate" title="{{ $category->name }}">{{ $category->name }}</h3>
                                    
                                    <div class="flex items-center text-sm text-gray-500 mb-3 {{ $category->end_date && $category->end_date->isPast() ? 'text-red-500 font-bold' : '' }}">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Deadline: {{ $category->end_date->format('M d, Y') }}
                                    </div>

                                    <p class="text-sm text-gray-600 line-clamp-2 mb-4 h-10">
                                        {{ $category->description }}
                                    </p>

                                    <div class="pt-4 border-t border-gray-50 mt-auto">
                                        @if($category->end_date && $category->end_date->isPast())
                                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-400 font-semibold text-xs uppercase tracking-widest cursor-not-allowed">
                                                Expired
                                            </button>
                                        @elseif($category->nominations_count > 0)
                                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-500 rounded-md font-semibold text-xs uppercase tracking-widest cursor-not-allowed">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                Submitted
                                            </button>
                                        @else
                                            <a href="{{ route('employee.nominations.create', $category) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                                Nominate Peer
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-12 text-center rounded-lg shadow-sm border border-gray-100">
                                <div class="mb-4 flex justify-center text-gray-300">
                                    <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No active nominations</h3>
                                <p class="text-gray-500">There are no nomination categories open at this time.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-8">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
