# Gamification Campaign Custom Level Fields - Hide for Purchase Products

## Summary

This implementation hides custom level fields (icon and label) when the target type is set to "purchase product" in the gamification campaign creation and editing forms.

## Files Created

### 1. **`public/js/gamification-campaign-form.js`**
   - Vanilla JavaScript solution (recommended)
   - No external dependencies
   - Works with all modern browsers
   - Features:
     - Auto-initialization on page load
     - Dynamic field visibility toggle
     - Support for multiple naming conventions
     - Handles dynamically added targets

### 2. **`public/js/gamification-campaign-alpine.js`**
   - Alpine.js solution
   - Better integration if your project uses Alpine.js
   - Lightweight and reactive
   - Uses Alpine.js event binding and DOM utilities

### 3. **`GAMIFICATION_CUSTOM_LEVELS_GUIDE.md`**
   - Complete implementation guide
   - Usage examples
   - Troubleshooting tips
   - Browser compatibility info

## Quick Start

### Step 1: Include the JavaScript in Your View

Add this to `resources/views/gamification/business/create.blade.php` and `edit.blade.php`:

```blade
<!-- At the end of the view file -->
@push('scripts')
    <script src="{{ asset('js/gamification-campaign-form.js') }}"></script>
@endpush
```

### Step 2: Done!
The script automatically initializes and handles all visibility toggling.

## How It Works

1. **On Page Load**: 
   - Scans for all target type select fields
   - Sets initial visibility based on current values
   
2. **On Target Type Change**:
   - If user selects "purchase": hides icon and label fields, clears values
   - For other types: shows icon and label fields

3. **Dynamic Rows**:
   - If new targets are added dynamically, simply call `GamificationForm.reinit()` to rebind listeners

## Feature Details

✅ **Hidden Fields When Target Type is "Purchase"**:
- Icon selector field
- Custom label text input field
- Field values are cleared to prevent submission

✅ **Visible Fields for Other Target Types**:
- Referral
- Nomination  
- Claim

## Integration Points

The implementation is frontend-only and requires no changes to:
- Controllers
- Models
- Database migrations
- Business logic

## Existing Form Structure

The solution works with your existing form structure that already stores:
- `targets[X][type]` - The target type
- `targets[X][icon]` - The icon for the target
- `targets[X][label]` - The custom label for the target
- `targets[X][value]` - The target value
- `targets[X][product_id]` - Product ID (for purchase targets)

## Behavior Examples

### Scenario 1: Creating Purchase Target
```
User selects type = "purchase"
↓
Icon field → Hidden
Label field → Hidden
Values → Cleared
```

### Scenario 2: Creating Referral Target
```
User selects type = "referral"
↓
Icon field → Visible
Label field → Visible
User can select icon and enter custom label
```

### Scenario 3: Changing Target Type
```
User changes from "referral" to "purchase"
↓
Icon & label fields → Hidden
Previous values → Cleared
```

## Optional: Backend Validation

If you want to add backend validation to prevent these fields from being saved for purchase targets:

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

## Testing Checklist

- [ ] Create campaign with "purchase" target → icon/label fields hidden
- [ ] Create campaign with "referral" target → icon/label fields visible
- [ ] Change target type from "referral" to "purchase" → fields hide
- [ ] Change target type from "purchase" to "referral" → fields appear
- [ ] Edit existing campaign → correct visibility for each target type
- [ ] Add new target dynamically → JavaScript properly initializes
- [ ] Form submits correctly → no errors with hidden fields

## Browser Support

- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- IE11: ⚠️ Requires polyfills for modern JS features

## Troubleshooting

**Fields not hiding?**
- Check browser console (F12) for JavaScript errors
- Verify script is loaded (check Network tab)
- Ensure form field names match expected pattern

**Not working with dynamic forms?**
- Call `GamificationForm.reinit()` after adding new rows
- Check that new elements have correct `name` attributes

**Can't find field wrappers?**
- Update the `findFieldWrapper()` function in the script
- Add your custom wrapper class selectors
- Check HTML structure of your form

## Files Reference

| File | Purpose | Type |
|------|---------|------|
| `public/js/gamification-campaign-form.js` | Main implementation | JavaScript (Vanilla) |
| `public/js/gamification-campaign-alpine.js` | Alpine.js integration | JavaScript (Alpine) |
| `GAMIFICATION_CUSTOM_LEVELS_GUIDE.md` | Detailed guide | Markdown |

## Next Steps

1. Copy the implementation guide to your project documentation
2. Include the JavaScript file in your views
3. Test the functionality
4. (Optional) Add backend validation

That's it! The feature is now active.
