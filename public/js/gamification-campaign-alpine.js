/**
 * Alpine.js Directive for Gamification Campaign Custom Level Fields
 * 
 * Usage in Blade:
 * <div x-data="gamificationCampaignForm()">
 *   <!-- Form fields -->
 * </div>
 */

function gamificationCampaignForm() {
    return {
        /**
         * Update visibility of custom level fields based on target type
         * @param {string} targetType - The selected target type ('purchase', 'referral', etc.)
         * @param {number} index - The target index
         */
        updateCustomLevelVisibility(targetType, index) {
            this.$nextTick(() => {
                const iconField = this.$el.querySelector(`[name="targets.${index}.icon"]`) || 
                                this.$el.querySelector(`[name="targets[${index}][icon]"]`);
                const labelField = this.$el.querySelector(`[name="targets.${index}.label"]`) || 
                                 this.$el.querySelector(`[name="targets[${index}][label]"]`);
                
                if (iconField) {
                    const iconWrapper = iconField.closest('.form-group, .field-group, .space-y-4 > div');
                    if (targetType === 'purchase') {
                        if (iconWrapper) iconWrapper.classList.add('hidden');
                        iconField.value = '';
                    } else {
                        if (iconWrapper) iconWrapper.classList.remove('hidden');
                    }
                }
                
                if (labelField) {
                    const labelWrapper = labelField.closest('.form-group, .field-group, .space-y-4 > div');
                    if (targetType === 'purchase') {
                        if (labelWrapper) labelWrapper.classList.add('hidden');
                        labelField.value = '';
                    } else {
                        if (labelWrapper) labelWrapper.classList.remove('hidden');
                    }
                }
            });
        },
        
        /**
         * Initialize the form with proper visibility state
         */
        init() {
            const typeSelects = this.$el.querySelectorAll('[name*="targets"][name*="type"]');
            typeSelects.forEach((select, index) => {
                this.updateCustomLevelVisibility(select.value, index);
            });
        }
    };
}

// Export for use in other scripts
if (typeof window !== 'undefined') {
    window.gamificationCampaignForm = gamificationCampaignForm;
}
