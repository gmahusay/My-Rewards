/**
 * Gamification Campaign Form Handler
 * 
 * Hides custom level fields (icon, label) when the target type is 'purchase'
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle target type changes to show/hide custom level fields
    const targetTypeSelects = document.querySelectorAll('[name*="targets"][name*="type"]');
    
    targetTypeSelects.forEach(function(select) {
        // Initial state
        updateCustomLevelVisibility(select);
        
        // Listen for changes
        select.addEventListener('change', function() {
            updateCustomLevelVisibility(this);
        });
    });
    
    /**
     * Update visibility of custom level fields based on target type
     * @param {HTMLElement} selectElement - The target type select element
     */
    function updateCustomLevelVisibility(selectElement) {
        const targetType = selectElement.value;
        
        // Get the parent container (usually a fieldset or div containing all fields for this target)
        const targetContainer = selectElement.closest('[data-target-row], .target-row, .target-item, fieldset');
        
        if (!targetContainer) {
            // Fallback: try to find fields by attribute pattern
            const fieldName = selectElement.name;
            const match = fieldName.match(/targets\.(\d+)\./);
            if (match) {
                const index = match[1];
                updateFieldsByIndex(index, targetType);
            }
            return;
        }
        
        // Hide/show custom level fields based on target type
        const iconField = targetContainer.querySelector('[name*="icon"]');
        const labelField = targetContainer.querySelector('[name*="label"]');
        const iconWrapper = iconField?.closest('.form-group, .field-group, div[class*="icon"]');
        const labelWrapper = labelField?.closest('.form-group, .field-group, div[class*="label"]');
        
        if (targetType === 'purchase') {
            // Hide custom fields for purchase target type
            if (iconWrapper) iconWrapper.style.display = 'none';
            if (labelWrapper) labelWrapper.style.display = 'none';
            if (iconField) iconField.value = '';
            if (labelField) labelField.value = '';
        } else {
            // Show custom fields for other target types
            if (iconWrapper) iconWrapper.style.display = '';
            if (labelWrapper) labelWrapper.style.display = '';
        }
    }
    
    /**
     * Fallback method to update fields by index
     * @param {string} index - The target index
     * @param {string} targetType - The selected target type
     */
    function updateFieldsByIndex(index, targetType) {
        const iconField = document.querySelector(`[name="targets[${index}][icon]"]`);
        const labelField = document.querySelector(`[name="targets[${index}][label]"]`);
        const iconInput = document.querySelector(`[name="targets.${index}.icon"]`);
        const labelInput = document.querySelector(`[name="targets.${index}.label"]`);
        
        const icon = iconField || iconInput;
        const label = labelField || labelInput;
        
        const iconWrapper = icon?.closest('.form-group, .field-group, div[class*="icon"]');
        const labelWrapper = label?.closest('.form-group, .field-group, div[class*="label"]');
        
        if (targetType === 'purchase') {
            if (iconWrapper) iconWrapper.style.display = 'none';
            if (labelWrapper) labelWrapper.style.display = 'none';
            if (icon) icon.value = '';
            if (label) label.value = '';
        } else {
            if (iconWrapper) iconWrapper.style.display = '';
            if (labelWrapper) labelWrapper.style.display = '';
        }
    }
    
    // Handle dynamic target additions (if using JavaScript to add new target rows)
    const addTargetButton = document.querySelector('[data-action="add-target"], .add-target-btn, button[type="button"][class*="add"]');
    if (addTargetButton) {
        addTargetButton.addEventListener('click', function(e) {
            // Wait for DOM update then re-bind event listeners
            setTimeout(function() {
                const newSelects = document.querySelectorAll('[name*="targets"][name*="type"]');
                newSelects.forEach(function(select) {
                    // Remove existing listeners (avoid duplicates)
                    const newSelect = select.cloneNode(true);
                    select.parentNode.replaceChild(newSelect, select);
                    
                    newSelect.addEventListener('change', function() {
                        updateCustomLevelVisibility(this);
                    });
                });
            }, 100);
        });
    }
});
