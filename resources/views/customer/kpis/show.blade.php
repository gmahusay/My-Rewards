<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }}
            </h2>
            <a href="{{ route(request()->routeIs('employee.*') ? 'employee.kpis.index' : 'customer.kpis.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to KPI Goals</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- KPI Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($category->image_path)
                            <div class="w-full h-48 overflow-hidden rounded-lg mb-6">
                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                             <div class="w-full h-48 bg-indigo-50 flex items-center justify-center rounded-lg mb-6">
                                <svg class="h-16 w-16 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                        @endif

                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-bold uppercase tracking-wider">
                                {{ number_format($category->points_reward) }} pts / achievement
                            </span>
                        </div>
                        
                        <div class="prose text-gray-600 mb-6">
                            {{ $category->description }}
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Goal Period</label>
                            <div class="text-sm text-gray-700">
                                {{ $category->start_date->format('M d, Y') }} - {{ $category->end_date->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit KPI Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Submit Your Achievement</h3>
                        <form action="{{ route(request()->routeIs('employee.*') ? 'employee.kpis.store' : 'customer.kpis.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            
                            <div>
                                <x-input-label for="description" :value="__('Achievement Description')" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Describe what you achieved..."></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="proof_image" :value="__('Proof of Achievement (Optional)')" />
                                <input type="file" id="proof_image" name="proof_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="mt-1 text-xs text-gray-500">Upload screenshot or photo as proof</p>
                                <x-input-error class="mt-2" :messages="$errors->get('proof_image')" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Submit KPI') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- My Submissions Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">My Submissions for this Goal</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($myKpis as $kpi)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($kpi->description, 60) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($kpi->proof_image_path)
                                            <a href="{{ Storage::url($kpi->proof_image_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-xs">View</a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-600">
                                        {{ $kpi->rewarded_points ? number_format($kpi->rewarded_points) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kpi->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        You haven't submitted any KPIs for this goal yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
