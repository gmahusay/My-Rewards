<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            @if($user->profile_photo_path)
                <div class="mb-2">
                    <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                </div>
            @endif
            <input id="profile_photo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                type="file" name="profile_photo" accept="image/*">
            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($user->hasRole('business'))
            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-md font-medium text-gray-900 mb-4">{{ __('Website Information') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="website_logo" :value="__('Website Logo')" />
                        @if($user->website_logo_path)
                            <div class="mb-2">
                                <img src="{{ Storage::url($user->website_logo_path) }}" alt="{{ $user->website_name }}" class="h-16 w-16 object-contain">
                            </div>
                        @endif
                        <input id="website_logo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                            type="file" name="website_logo" accept="image/*">
                        <x-input-error :messages="$errors->get('website_logo')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="website_name" :value="__('Website Name')" />
                        <x-text-input id="website_name" name="website_name" type="text" class="mt-1 block w-full" :value="old('website_name', $user->website_name)" />
                        <x-input-error class="mt-2" :messages="$errors->get('website_name')" />
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-md font-medium text-gray-900 mb-4">{{ __('Company Information') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="company_name" :value="__('Company Name')" />
                        <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $user->company_name)" />
                        <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                    </div>

                    <div>
                        <x-input-label for="company_contact_person" :value="__('Contact Person')" />
                        <x-text-input id="company_contact_person" name="company_contact_person" type="text" class="mt-1 block w-full" :value="old('company_contact_person', $user->company_contact_person)" />
                        <x-input-error class="mt-2" :messages="$errors->get('company_contact_person')" />
                    </div>

                    <div>
                        <x-input-label for="company_contact_number" :value="__('Contact Number')" />
                        <x-text-input id="company_contact_number" name="company_contact_number" type="text" class="mt-1 block w-full" :value="old('company_contact_number', $user->company_contact_number)" />
                        <x-input-error class="mt-2" :messages="$errors->get('company_contact_number')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="company_address" :value="__('Company Address')" />
                        <textarea id="company_address" name="company_address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('company_address', $user->company_address) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
