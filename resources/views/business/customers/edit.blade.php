<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Customer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <form method="POST" action="{{ route('business.customers.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Profile Photo -->
                        <div class="mt-4">
                            <x-input-label for="profile_photo" :value="__('Profile Photo (Optional)')" />
                            @if($user->profile_photo_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                                </div>
                            @endif
                            <input id="profile_photo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                                type="file" name="profile_photo" accept="image/*">
                            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('business.customers.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Customer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
