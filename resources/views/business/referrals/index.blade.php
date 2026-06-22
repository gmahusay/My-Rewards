<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Referrals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referrer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referred Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($referrals as $referral)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $referral->referrer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $referral->referrer->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $referral->category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $referral->referred_email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-600 font-bold">
                                            {{ number_format($referral->category->points_reward) }} pts
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($referral->status === 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Approved
                                                </span>
                                            @elseif($referral->status === 'rejected')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    Rejected
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $referral->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($referral->status === 'pending')
                                                <div class="flex justify-end gap-2 text-xs">
                                                    <form method="POST" action="{{ route('business.referrals.approve', $referral) }}">
                                                        @csrf
                                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition" onclick="return confirm('Approve this referral and award points?');">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('business.referrals.reject', $referral) }}">
                                                        @csrf
                                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition" onclick="return confirm('Reject this referral?');">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($referral->status === 'approved' && $referral->rewarded_points)
                                                 <span class="text-xs text-green-600 font-semibold">Awarded {{ $referral->rewarded_points }} pts</span>
                                            @else
                                                 <span class="text-xs text-gray-400">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <p>No referrals received yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $referrals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
