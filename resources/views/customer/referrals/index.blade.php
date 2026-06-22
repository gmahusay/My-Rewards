<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Referral Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route(request()->routeIs('employee.*') ? 'employee.referrals.show' : 'customer.referrals.show', $category) }}" 
                       class="block bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 flex flex-col h-full hover:shadow-md transition duration-150 ease-in-out group">
                        @if($category->image_path)
                            <div class="h-40 w-full overflow-hidden">
                                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-150">
                            </div>
                        @else
                            <div class="h-40 w-full bg-indigo-50 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600">{{ $category->name }}</h4>
                            </div>
                            
                            <div class="mb-4">
                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase tracking-wider">
                                    {{ number_format($category->points_reward) }} pts / referral
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-3">{{ $category->description }}</p>

                            <div class="mt-auto pt-4 flex items-center text-indigo-600 font-semibold text-sm">
                                View Details & Refer
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </a>
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
