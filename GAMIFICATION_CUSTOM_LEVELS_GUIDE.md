# Gamification Campaign Custom Level Fields - Implementation Guide

## Overview
Hide custom level fields (icon and label) when the target type is "purchase product" in the gamification campaign creation/editing form.

## Solution Files

### 1. JavaScript Implementation
- **File**: `public/js/gamification-campaign-form.js`
  - Vanilla JavaScript solution
  - Works with standard form attributes
  - No external dependencies required

- **File**: `public/js/gamification-campaign-alpine.js`
  - Alpine.js solution
  - Better integration with modern Laravel projects
  - Recommended if using Alpine.js in your project

## Integration Instructions

### Option 1: Vanilla JavaScript (Recommended for standard forms)

Add this to your `resources/views/gamification/business/create.blade.php` and `edit.blade.php` files:

```blade
<!-- At the end of the file, before closing body tag -->
@push('scripts')
    <script src="{{ asset('js/gamification-campaign-form.js') }}"></script>
@endpush
```

### Option 2: Alpine.js (Recommended if using Alpine.js)

Modify your form container in the view to include Alpine.js binding:

```blade
<form x-data="gamificationCampaignForm()" @submit.prevent="submitForm">
    <!-- Your form fields here -->
    
    <div class="targets-section">
        @foreach ($request->old('targets', $campaign->targets ?? []) as $index => $target)
            <div class="target-item">
                <!-- Type field -->
                <select name="targets.{{ $index }}.type" 
                        @change="updateCustomLevelVisibility($event.target.value, {{ $index }})">
                    <option value="purchase">Purchase Product</option>
                    <option value="referral">Refer a User</option>
                    <option value="nomination">Nominate Someone</option>
                    <option value="claim">Submit a Claim</option>
                </select>
                
                <!-- Icon field (hidden for purchase) -->
                <div class="form-group">
                    <label for="icon_{{ $index }}">Icon</label>
                    <select name="targets.{{ $index }}.icon" id="icon_{{ $index }}">
                        <option value="">Select an icon</option>
                        @foreach ($icons as $icon => $label)
                            <option value="{{ $icon }}"{{ old("targets.$index.icon") == $icon ? ' selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Label field (hidden for purchase) -->
                <div class="form-group">
                    <label for="label_{{ $index }}">Custom Label</label>
                    <input type="text" 
                           name="targets.{{ $index }}.label" 
                           id="label_{{ $index }}" 
                           value="{{ old("targets.$index.label") }}"
                           placeholder="Leave empty for default">
                </div>
                
                <!-- Value field -->
                <div class="form-group">
                    <label for="value_{{ $index }}">Target Value</label>
                    <input type="number" 
                           name="targets.{{ $index }}.value" 
                           id="value_{{ $index }}" 
                           value="{{ old("targets.$index.value") }}"
                           required>
                </div>
            </div>
        @endforeach
    </div>
</form>

@push('scripts')
    <script src="{{ asset('js/gamification-campaign-alpine.js') }}"></script>
@endpush
```

### Option 3: Pure CSS Solution (Simplest)

If you want to avoid JavaScript, you can use CSS attribute selectors:

```blade
<style>
    /* Hide icon and label fields when the related type field has value 'purchase' */
    [name*="type"][value="purchase"]:checked ~ [name*="icon"],
    [name*="type"][value="purchase"]:checked ~ [name*="label"] {
        display: none !important;
    }
</style>
```

**Note**: The CSS solution requires specific HTML structure and may not work with all form layouts.

## How It Works

### Vanilla JavaScript
1. Listens for `DOMContentLoaded` event
2. Finds all target type select elements
3. Attaches change event listeners
4. When target type changes:
   - If type is "purchase": hides icon and label fields, clears their values
   - Otherwise: shows icon and label fields
5. Handles dynamically added target rows

### Alpine.js
1. Uses Alpine.js's `x-data` and event binding (`@change`)
2. Triggers `updateCustomLevelVisibility()` on select change
3. Uses Alpine's `$nextTick()` for DOM updates
4. Adds/removes `hidden` class to field containers

## Field Hiding Logic

When target type is `purchase`:
- ✅ Hides the icon selector field
- ✅ Hides the custom label text input field
- ✅ Clears any existing values in these fields
- ✅ Prevents submission of these fields for purchase targets

When target type is `referral`, `nomination`, or `claim`:
- ✅ Shows the icon selector field
- ✅ Shows the custom label text input field
- ✅ Allows users to customize the appearance

## Database Considerations

Currently, the system allows storing `icon` and `label` for all target types. You may want to:

1. **Option A**: Keep allowing storage but hide from UI (current implementation)
2. **Option B**: Add backend validation to prevent saving these fields for purchase targets:

```php
// In GamificationCampaignController.php store() method
foreach ($request->targets as $index => $target) {
    $targetData = [
        'level'        => $index + 1,
        'target_type'  => $target['type'],
        'product_id'   => $target['product_id'] ?? null,
        'target_value' => $target['value'],
    ];
    
    // Only include icon and label for non-purchase targets
    if ($target['type'] !== 'purchase') {
        $targetData['icon']  = $target['icon'] ?? null;
        $targetData['label'] = $target['label'] ?? null;
    }
    
    $campaign->targets()->create($targetData);
}
```

## Testing

After implementation, test the following scenarios:

1. ✅ Create a campaign with a "purchase" target
   - Verify icon and label fields are hidden
   
2. ✅ Create a campaign with "referral", "nomination", or "claim" target
   - Verify icon and label fields are visible
   
3. ✅ Change target type from "referral" to "purchase"
   - Verify fields hide and values are cleared
   
4. ✅ Change target type from "purchase" to "referral"
   - Verify fields become visible again
   
5. ✅ Edit existing campaign
   - Verify field visibility matches target types

## Browser Compatibility

- **Vanilla JS**: Works in all modern browsers (IE11+)
- **Alpine.js**: Works in all modern browsers
- **CSS only**: Works in all browsers supporting attribute selectors

## Troubleshooting

### Fields not hiding?
1. Check browser console for JavaScript errors
2. Verify the form has correct `name` attributes (`targets.X.type`, `targets.X.icon`, etc.)
3. Ensure CSS classes used for hiding are correct
4. Check that script file is loaded (use browser DevTools Network tab)

### Values not clearing?
1. Verify form submission doesn't include hidden field values
2. Check that form handles hidden field submission correctly
3. Consider server-side validation to clear purchase target icon/label

### Dynamic rows not working?
1. Ensure new target rows have correct `name` attribute structure
2. Re-bind event listeners after adding new rows
3. Check for JavaScript errors in console

## Additional Notes

- This implementation respects the existing form structure
- No changes to controller or model required (frontend only)
- Works with both create and edit forms
- Handles server-side validation errors gracefully
- Values are cleared but not submitted for purchase targets
