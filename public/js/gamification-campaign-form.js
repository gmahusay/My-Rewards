/**
 * Gamification Campaign Form Handler
 * 
 * Hides custom level fields (icon, label) when the target type is 'purchase'
 * 
 * This script automatically:
 * 1. Initializes visibility state on page load
 * 2. Updates visibility when target type changes
 * 3. Handles dynamically added target rows
 * 4. Works with multiple form element naming conventions
 */

(function() {
    'use strict';

    const GamificationForm = {
        /**
         * Find the target container for a given select element
         * @param {HTMLElement} selectElement 
         * @returns {HTMLElement|null}
         */
        getTargetContainer(selectElement) {
            // Try common container selectors
            const containerSelectors = [
                '[data-target-row]',
                '.target-row',
                '.target-item',
                '.target-group',
                'fieldset',
                '.target-container'
            ];

            for (let selector of containerSelectors) {
                const container = selectElement.closest(selector);
                if (container) return container;
            }

            return null;
        },

        /**
         * Extract index from field name (works with targets.0.type or targets[0][type])
         * @param {string} fieldName 
         * @returns {string|null}
         */
        getIndexFromFieldName(fieldName) {
            const match = fieldName.match(/targets[\.\[](\d+)[\]\.]/) || 
                         fieldName.match(/targets\[(\d+)\]/);
            return match ? match[1] : null;
        },

        /**
         * Update visibility of custom level fields based on target type
         * @param {HTMLElement} selectElement - The target type select element
         */
        updateCustomLevelVisibility(selectElement) {
            const targetType = selectElement.value;
            const fieldName = selectElement.name;
            const index = this.getIndexFromFieldName(fieldName);

            if (!index) return;

            const container = this.getTargetContainer(selectElement);
            let iconField, labelField, iconWrapper, labelWrapper;

            if (container) {
                // Look for icon and label fields within the container
                iconField = container.querySelector('[name*="icon"]');
                labelField = container.querySelector('[name*="label"]');
            } else {
                // Fallback: search by specific name patterns
                iconField = document.querySelector(`[name="targets[${index}][icon]"]`) ||
                           document.querySelector(`[name="targets.${index}.icon"]`);
                labelField = document.querySelector(`[name="targets[${index}][label]"]`) ||
                            document.querySelector(`[name="targets.${index}.label"]`);
            }

            // Find the wrappers for these fields
            if (iconField) {
                iconWrapper = this.findFieldWrapper(iconField);
            }
            if (labelField) {
                labelWrapper = this.findFieldWrapper(labelField);
            }

            // Apply visibility rules
            if (targetType === 'purchase') {
                this.hideField(iconField, iconWrapper);
                this.hideField(labelField, labelWrapper);
            } else {
                this.showField(iconField, iconWrapper);
                this.showField(labelField, labelWrapper);
            }
        },

        /**
         * Find the wrapper element for a form field
         * @param {HTMLElement} field 
         * @returns {HTMLElement|null}
         */
        findFieldWrapper(field) {
            if (!field) return null;

            // Try common wrapper selectors
            const wrappers = [
                '.form-group',
                '.field-group',
                '.form-field',
                '.mb-4',
                '.mb-6',
                '[class*="space-y"]'
            ];

            for (let selector of wrappers) {
                const wrapper = field.closest(selector);
                if (wrapper) return wrapper;
            }

            return field.parentElement;
        },

        /**
         * Hide a form field and clear its value
         * @param {HTMLElement} field 
         * @param {HTMLElement} wrapper 
         */
        hideField(field, wrapper) {
            if (wrapper) {
                wrapper.style.display = 'none';
                wrapper.classList.add('hidden');
            }
            if (field) {
                field.style.display = 'none';
                field.value = '';
            }
        },

        /**
         * Show a form field
         * @param {HTMLElement} field 
         * @param {HTMLElement} wrapper 
         */
        showField(field, wrapper) {
            if (wrapper) {
                wrapper.style.display = '';
                wrapper.classList.remove('hidden');
            }
            if (field) {
                field.style.display = '';
            }
        },

        /**
         * Initialize all target type selects
         */
        init() {
            const targetTypeSelects = document.querySelectorAll('[name*="targets"][name*="type"]');

            targetTypeSelects.forEach((select) => {
                // Set initial visibility state
                this.updateCustomLevelVisibility(select);

                // Bind change event
                select.addEventListener('change', () => {
                    this.updateCustomLevelVisibility(select);
                });
            });
        },

        /**
         * Re-initialize after new targets are added dynamically
         */
        reinit() {
            this.init();
        }
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            GamificationForm.init();
        });
    } else {
        GamificationForm.init();
    }

    // Expose to global scope for external use
    window.GamificationForm = GamificationForm;
})();
