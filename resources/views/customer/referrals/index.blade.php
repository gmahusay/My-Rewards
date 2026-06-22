<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Referral Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 flex flex-col h-full">
                        @if($category->image_path)
                            <div class="h-40 w-full overflow-hidden">
                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-bold text-gray-900">{{ $category->name }}</h4>
                                @if($category->has_joined ?? false)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Joined
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                    {{ number_format($category->points_reward) }} pts / referral
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-3 flex-grow">{{ $category->description }}</p>

                            <div class="mt-4 flex items-center gap-2">
                                <a href="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.show' : 'customer.referrals.show', $category) }}"
                                   class="flex-1 text-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md transition">
                                    View Details
                                </a>
                                @if(!($category->has_joined ?? false))
                                    <form action="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.join' : 'customer.referrals.join', $category) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md transition">
                                            Join
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-lg shadow-sm">
                        <svg class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="text-lg font-medium">No active referral campaigns at the moment.</p>
                        <p class="text-sm">Check back later for new opportunities!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

