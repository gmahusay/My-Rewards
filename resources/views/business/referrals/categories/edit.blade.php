<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Referral Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('business.referrals.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" :value="__('Campaign Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        <!-- referral_link is auto-generated server-side. Display example link for current user -->
                        <div>
                            <x-input-label :value="__('Shareable Link (example for you)')" />
                            @php
                                $userToken = auth()->user()->referral_identifier ?? '';
                                $publicUrl = $category->referral_link && $userToken ? route('referrals.public', [$category->referral_link, $userToken]) : '#';
                            @endphp
                            <div class="mt-1 text-sm text-indigo-600 truncate max-w-full">
                                <a href="{{ $publicUrl }}" target="_blank">{{ $publicUrl }}</a>
                            </div>
                        </div>
                            <x-input-error class="mt-2" :messages="$errors->get('referral_link')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $category->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="points_reward" :value="__('Points Reward per Successful Referral')" />
                            <x-text-input id="points_reward" name="points_reward" type="number" class="mt-1 block w-full" :value="old('points_reward', $category->points_reward)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('points_reward')" />
                        </div>

                        <div>
                            <x-input-label for="is_active" :value="__('Status')" />
                            <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="1" {{ old('is_active', $category->is_active) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('is_active', $category->is_active) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Update Image (Optional)')" />
                            @if($category->image_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($category->image_path) }}" alt="Current Image" class="h-20 w-20 object-cover rounded shadow-sm">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG up to 2MB. Leave empty to keep current image.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('business.referrals.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Category') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
