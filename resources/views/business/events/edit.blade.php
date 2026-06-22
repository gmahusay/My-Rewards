<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event: ') }} {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('business.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Event Image -->
                        <div>
                            <x-input-label for="image" :value="__('Event Header Image (Optional)')" />
                            @if($event->image_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-500 mt-1">Current Image</p>
                                </div>
                            @endif
                            <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-xs text-gray-500">Wide aspect ratio recommended (Max 2MB: JPG, PNG, JPEG).</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Event Title -->
                        <div>
                            <x-input-label for="title" :value="__('Event Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $event->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Location -->
                            <div>
                                <x-input-label for="location" :value="__('Location / Online Link')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event->location)" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Reward Points -->
                            <div>
                                <x-input-label for="points_reward" :value="__('Points Reward')" />
                                <x-text-input id="points_reward" class="block mt-1 w-full" type="number" name="points_reward" :value="old('points_reward', $event->points_reward)" required min="0" />
                                <x-input-error :messages="$errors->get('points_reward')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Date & Time -->
                            <div>
                                <x-input-label for="event_date" :value="__('Event Date & Time')" />
                                <x-text-input id="event_date" class="block mt-1 w-full" type="datetime-local" name="event_date" :value="old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center pt-8">
                                <label for="is_active" class="inline-flex items-center">
                                    <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Event is Active') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('business.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Event') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
