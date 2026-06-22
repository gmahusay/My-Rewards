<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nomination Categories') }}
            </h2>
            <a href="{{ route('business.nominations.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Create Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Metrics Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Nominations</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($metrics['total_active']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Awarded Categories</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($metrics['total_awarded']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-amber-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Points Awarded</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($metrics['total_points_awarded']) }} pts</div>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('business.nominations.categories.results', $category) }}" class="block" title="View Results">
                                                @if($category->image_path)
                                                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-full object-cover border border-gray-100 shadow-sm">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('business.nominations.categories.results', $category) }}" class="block">
                                                <div class="text-sm font-bold text-gray-900 hover:text-indigo-600">{{ $category->name }}</div>
                                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $category->description }}</div>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $category->start_date->format('M d') }} - {{ $category->end_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-600 font-bold">
                                            {{ number_format($category->points_reward) }} pts
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($category->winner_id)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                    Awarded
                                                </span>
                                            @elseif($category->isActive())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-3 items-center">
                                                <a href="{{ route('business.nominations.categories.results', $category) }}" class="text-amber-600 hover:text-amber-900 font-bold hover:underline">Results</a>
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('business.nominations.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('business.nominations.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                                <p>No nomination categories created yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
