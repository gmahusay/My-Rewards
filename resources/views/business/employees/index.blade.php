<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Employee List</h3>
                        <a href="{{ route('business.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add New Employee
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div x-data="{ showPointsModal: false, selectedUser: null, userId: null }">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($employee->profile_photo_path)
                                                    <img src="{{ Storage::url($employee->profile_photo_path) }}" alt="{{ $employee->name }}" class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                                        {{ substr($employee->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($employee->points) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center space-x-3">
                                                    <button @click="showPointsModal = true; selectedUser = '{{ $employee->name }}'; userId = {{ $employee->id }}" class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 rounded px-2 py-1 text-xs">Allocator Points</button>
                                                    <a href="{{ route('business.employees.edit', $employee->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                                    <form action="{{ route('business.employees.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No employees found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Allocate Points Modal -->
                        <div x-show="showPointsModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showPointsModal = false">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <form x-bind:action="'/business/users/' + userId + '/points'" method="POST">
                                        @csrf
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                        Allocate Points to <span x-text="selectedUser"></span>
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            Enter the amount of points to transfer to this employee.
                                                        </p>
                                                        <div class="mt-4">
                                                            <input type="number" name="amount" min="1" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Amount">
                                                            <p class="mt-1 text-xs text-gray-500">Your current balance: {{ number_format(Auth::user()->points) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                Transfer Points
                                            </button>
                                            <button type="button" @click="showPointsModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
