<!-- 
    Target Form Component
    
    This is a reusable component that displays the form fields for a single
    campaign target/level. It's included in the main campaign form.
    
    File: resources/views/gamification/business/_target_form.blade.php
    
    Usage:
    @include('gamification.business._target_form', [
        'index' => 0,
        'target' => $target,
        'targetTypes' => $targetTypes,
        'icons' => $icons,
        'products' => $products
    ])
-->

<div class="space-y-4">
    <!-- Target Type Selection -->
    <div class="form-group">
        <label for="type_{{ $index }}" class="block text-sm font-medium text-gray-700">
            Target Type <span class="text-red-500">*</span>
        </label>
        <select name="targets[{{ $index }}][type]" 
                id="type_{{ $index }}"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                required>
            <option value="">-- Select Target Type --</option>
            @foreach($targetTypes as $value => $label)
                <option value="{{ $value }}" 
                        {{ old("targets.$index.type") == $value || ($target && $target->target_type == $value) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error("targets.$index.type")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Icon Selection (Hidden for Purchase Type) -->
    <div class="form-group">
        <label for="icon_{{ $index }}" class="block text-sm font-medium text-gray-700">
            Icon (Custom Level Appearance)
        </label>
        <select name="targets[{{ $index }}][icon]" 
                id="icon_{{ $index }}"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Select an Icon --</option>
            @foreach($icons as $iconClass => $iconLabel)
                <option value="{{ $iconClass }}" 
                        {{ old("targets.$index.icon") == $iconClass || ($target && $target->icon == $iconClass) ? 'selected' : '' }}>
                    {{ $iconLabel }}
                </option>
            @endforeach
        </select>
        <p class="text-gray-500 text-xs mt-1">Icon displayed in the campaign progress. Hidden when target type is 'purchase'.</p>
        @error("targets.$index.icon")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Custom Label (Hidden for Purchase Type) -->
    <div class="form-group">
        <label for="label_{{ $index }}" class="block text-sm font-medium text-gray-700">
            Custom Label
        </label>
        <input type="text" 
               name="targets[{{ $index }}][label]" 
               id="label_{{ $index }}"
               value="{{ old("targets.$index.label", $target ? $target->label : '') }}"
               placeholder="e.g., 'Buy 5 products', 'Refer a friend'. Leave empty for default."
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        <p class="text-gray-500 text-xs mt-1">Custom text to display for this level. Hidden when target type is 'purchase'.</p>
        @error("targets.$index.label")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Target Value -->
    <div class="form-group">
        <label for="value_{{ $index }}" class="block text-sm font-medium text-gray-700">
            Target Value <span class="text-red-500">*</span>
        </label>
        <input type="number" 
               name="targets[{{ $index }}][value]" 
               id="value_{{ $index }}"
               value="{{ old("targets.$index.value", $target ? $target->target_value : '') }}"
               min="1"
               placeholder="e.g., 5, 10, 100"
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
               required>
        <p class="text-gray-500 text-xs mt-1">
            How many actions are required to complete this level?
            <br>
            For <strong>purchase</strong>: quantity of units to buy
            <br>
            For <strong>referral</strong>: number of users to refer
            <br>
            For <strong>claim</strong>: number of claims to submit
            <br>
            For <strong>nomination</strong>: number of nominations to make
        </p>
        @error("targets.$index.value")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Product Selection (Only for Purchase Type) -->
    <div class="form-group">
        <label for="product_{{ $index }}" class="block text-sm font-medium text-gray-700">
            Product (Purchase Targets Only)
        </label>
        <select name="targets[{{ $index }}][product_id]" 
                id="product_{{ $index }}"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Applies to All Products --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" 
                        {{ old("targets.$index.product_id") == $product->id || ($target && $target->product_id == $product->id) ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
        <p class="text-gray-500 text-xs mt-1">Optional: Restrict this level to a specific product. Leave blank to apply to all products.</p>
        @error("targets.$index.product_id")
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<style>
    /* CSS classes to help with visibility control if JS fails */
    .hidden {
        display: none !important;
    }
</style>
