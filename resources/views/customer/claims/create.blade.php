<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit New Claim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $routePrefix = Auth::user()->hasRole('employee') ? 'employee' : 'customer';
                    @endphp
                    <form method="POST" action="{{ route($routePrefix . '.claims.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="business" :value="__('Submitting To')" />
                                <x-text-input id="business" class="block mt-1 w-full bg-gray-50" type="text" value="{{ $business->name }}" disabled />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Claim Category')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id') == $category->id || request('category_id') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ number_format($category->points_reward) }} pts)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="store_name" :value="__('Store Name')" />
                                <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full" :value="old('store_name')" placeholder="e.g. Downtown Mall Branch" required />
                                <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
                            </div>

                            <div>
                                <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                                <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full" :value="old('invoice_number')" placeholder="e.g. INV-123456" required />
                                <x-input-error class="mt-2" :messages="$errors->get('invoice_number')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Claim Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus placeholder="e.g. Service Rebate Request" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Describe the details of your claim...">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Claimed Amount')" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="pl-7 block w-full" :value="old('amount')" placeholder="0.00" required />
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <x-input-label for="document" :value="__('Supporting Document (PDF, JPG, PNG)')" />
                            <input type="file" id="document" name="document" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-xs text-gray-500">Max size: 5MB</p>
                            <x-input-error class="mt-2" :messages="$errors->get('document')" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route($routePrefix . '.claims.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Submit Claim') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
