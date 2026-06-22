<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Nomination') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100 italic text-indigo-800 text-sm">
                        You are nominating a colleague for the category: <strong class="not-italic">{{ $category->name }}</strong>. 
                        Awarding: <strong class="not-italic text-amber-600">{{ number_format($category->points_reward) }} pts</strong>
                    </div>

                    <form method="POST" action="{{ route('employee.nominations.store', $category) }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="nominee_id" :value="__('Select Colleague')" />
                            <select id="nominee_id" name="nominee_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required autofocus>
                                <option value="">-- Choose a peer --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('nominee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }} ({{ $employee->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('nominee_id')" />
                        </div>

                        <div>
                            <x-input-label for="reason" :value="__('Reason for Nomination')" />
                            <textarea id="reason" name="reason" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Why does this person deserve to win? (Minimum 10 characters)">{{ old('reason') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Be descriptive! Your feedback helps management decide.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('employee.nominations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Submit Nomination') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
