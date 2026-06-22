<!-- 
    Example Blade View Structure for Gamification Campaign Form
    
    This example shows how to structure your form to work with the
    gamification-campaign-form.js script that hides custom level fields
    when the target type is 'purchase'.
    
    File: resources/views/gamification/business/create.blade.php
-->

@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Create Gamification Campaign</h1>

    <form action="{{ route('business.gamification.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Campaign Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" 
                   value="{{ old('title') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                   required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Campaign Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description') }}</textarea>
        </div>

        <!-- Campaign Targets Section -->
        <fieldset class="mb-8 border-l-4 border-blue-500 pl-6">
            <legend class="text-lg font-semibold text-gray-900 mb-4">Campaign Targets/Levels</legend>

            <div id="targets-container" class="space-y-6">
                @forelse(old('targets', []) as $index => $target)
                    <div class="target-item border rounded-lg p-6 bg-gray-50" data-target-row>
                        @include('gamification.business._target_form', [
                            'index' => $index,
                            'target' => $target,
                            'targetTypes' => $targetTypes,
                            'icons' => $icons,
                            'products' => $products
                        ])
                    </div>
                @empty
                    <!-- Default: show one empty target form -->
                    <div class="target-item border rounded-lg p-6 bg-gray-50" data-target-row>
                        @include('gamification.business._target_form', [
                            'index' => 0,
                            'target' => null,
                            'targetTypes' => $targetTypes,
                            'icons' => $icons,
                            'products' => $products
                        ])
                    </div>
                @endforelse
            </div>

            <button type="button" id="add-target-btn" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                + Add Another Target
            </button>
        </fieldset>

        <!-- Reward Points -->
        <div class="mb-6">
            <label for="reward_points" class="block text-sm font-medium text-gray-700">Reward Points (for completing campaign)</label>
            <input type="number" name="reward_points" id="reward_points"
                   value="{{ old('reward_points', 0) }}"
                   min="0"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                   required>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                Create Campaign
            </button>
            <a href="{{ route('business.gamification.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
    <!-- Load the gamification form handler -->
    <script src="{{ asset('js/gamification-campaign-form.js') }}"></script>
    <script>
        // Handle adding new target rows dynamically
        document.getElementById('add-target-btn').addEventListener('click', function() {
            const container = document.getElementById('targets-container');
            const index = container.querySelectorAll('.target-item').length;
            
            // Clone the first target item and clear its values
            const template = container.querySelector('.target-item').cloneNode(true);
            
            // Update all field names and IDs in the template
            template.querySelectorAll('[name*="targets"]').forEach(field => {
                const oldName = field.name;
                const newName = oldName.replace(/targets\[\d+\]/, `targets[${index}]`)
                                       .replace(/targets\.\d+\./, `targets.${index}.`);
                field.name = newName;
                field.id = newName;
                field.value = '';
            });
            
            // Add the new row
            container.appendChild(template);
            
            // Re-initialize event listeners for the new row
            if (window.GamificationForm) {
                GamificationForm.reinit();
            }
        });
    </script>
@endpush
