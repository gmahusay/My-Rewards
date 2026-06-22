<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Referral Categories') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('business.referrals.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Incoming Referrals
                </a>
                <a href="{{ route('business.referrals.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Create Category
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Metrics Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Categories</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($metrics['total_active']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Referrals</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($metrics['total_referrals']) }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-amber-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Points Awarded</div>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name/Link</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referrals</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($category->image_path)
                                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-full object-cover border border-gray-100 shadow-sm">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900">
                                                <a href="{{ route('business.referrals.categories.show', $category) }}" class="hover:text-indigo-600 hover:underline">
                                                    {{ $category->name }}
                                                </a>
                                            </div>
                                                <div class="text-xs text-indigo-600 truncate max-w-xs block">
                                                    @php
                                                        $userToken = auth()->user()->referral_identifier ?? '';
                                                        $publicUrl = $category->referral_link && $userToken ? route('referrals.public', [$category->referral_link, $userToken]) : '#';
                                                    @endphp
                                                    <a href="{{ $publicUrl }}" target="_blank" class="hover:underline flex items-center">
                                                        {{ $publicUrl }}
                                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-600 font-bold">
                                            {{ number_format($category->points_reward) }} pts
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $category->referrals->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($category->is_active)
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
                                                <a href="{{ route('business.referrals.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('business.referrals.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
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
                                                <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                <p>No referral categories created yet.</p>
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
