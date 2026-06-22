<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Claim Category') }}
            </h2>
            <a href="{{ route('business.claims.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to List</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('business.claims.categories.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="image" :value="__('Category Image (Optional)')" />
                            <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-xs text-gray-500">Square image recommended (Max 2MB: JPG, PNG, JPEG).</p>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Category Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('Expiration Date (Optional)')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date')" min="{{ date('Y-m-d') }}" />
                                <p class="mt-1 text-xs text-gray-500">Claims cannot be submitted after this date.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="points_reward" :value="__('Points Reward')" />
                            <x-text-input id="points_reward" name="points_reward" type="number" class="mt-1 block w-full" :value="old('points_reward', 0)" required />
                            <p class="mt-1 text-xs text-gray-500">Number of points awarded to the user upon claim approval.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('points_reward')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Category') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
