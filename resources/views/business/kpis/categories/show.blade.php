<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('KPI Category Details') }}
            </h2>
            <a href="{{ route('business.kpis.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Categories</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Category Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col md:flex-row gap-6">
                    @if($category->image_path)
                        <div class="w-full md:w-1/4">
                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full rounded-lg shadow-sm border border-gray-100">
                        </div>
                    @endif

                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Created on {{ $category->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-amber-600 font-bold text-lg">{{ number_format($category->points_reward) }} pts / KPI</span>
                            </div>
                        </div>

                        <div class="mt-4 prose text-gray-600">
                            {{ $category->description }}
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Start Date</label>
                                <div class="mt-1 text-lg font-semibold text-gray-900">{{ $category->start_date->format('M d, Y') }}</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">End Date</label>
                                <div class="mt-1 text-lg font-semibold text-gray-900">{{ $category->end_date->format('M d, Y') }}</div>
                            </div>
                        </div>

                         <div class="mt-6 flex gap-3">
                            <a href="{{ route('business.kpis.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Edit Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Submissions List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">KPI Submissions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted On</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kpis as $kpi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $kpi->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $kpi->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($kpi->proof_image_path)
                                            <a href="{{ Storage::url($kpi->proof_image_path) }}" target="_blank" class="text-indigo-600 hover:underline text-sm flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                View Proof
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No proof uploaded</span>
                                        @endif
                                        @if($kpi->description)
                                            <div class="text-xs text-gray-500 mt-1">"{{ Str::limit($kpi->description, 50) }}"</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kpi->created_at->format('M d, Y') }}
                                        <span class="text-xs block text-gray-400">{{ $kpi->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($kpi->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($kpi->status === 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($kpi->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-bold text-amber-800 bg-amber-100 rounded-full">
                                                {{ number_format($kpi->rewarded_points) }} pts
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($kpi->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('business.kpis.approve', $kpi) }}" method="POST" onsubmit="return confirm('Approve this KPI submission? Points will be awarded to the user.');">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-bold">Approve</button>
                                                </form>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('business.kpis.reject', $kpi) }}" method="POST" onsubmit="return confirm('Reject this KPI submission?');">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        <p class="mt-4 text-lg font-medium">No KPI submissions yet</p>
                                        <p class="mt-1">Users haven't submitted any KPIs for this category.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $kpis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
