<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Gamification Campaign</h2>
            <a href="{{ route('business.gamification.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Campaigns</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('business.gamification.store') }}" enctype="multipart/form-data" id="campaign-form" class="space-y-8">
                        @csrf

                        {{-- Campaign Logo --}}
                        <div class="space-y-3">
                            <h3 class="text-base font-semibold text-gray-900 border-b pb-2">Campaign Logo</h3>
                            <div class="flex items-start gap-6">
                                <div id="logo-preview-wrapper" class="shrink-0 h-24 w-24 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden">
                                    <img id="logo-preview" src="" alt="Logo Preview" class="hidden h-24 w-24 object-cover rounded-xl" />
                                    <svg id="logo-placeholder" class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <x-input-label for="logo" :value="__('Campaign Logo')" />
                                    <input type="file" id="logo" name="logo" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        onchange="previewLogo(this)" />
                                    <p class="mt-1 text-xs text-gray-500">Recommended: Square image. Max 2MB (JPG, PNG, JPEG, WEBP, GIF).</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                                </div>
                            </div>
                        </div>

                        {{-- Basic Info --}}
                        <div class="space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 border-b pb-2">Campaign Details</h3>

                            <div>
                                <x-input-label for="title" :value="__('Campaign Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                              :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="start_date" :value="__('Start Date (Optional)')" />
                                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                                                  :value="old('start_date')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                                </div>
                                <div>
                                    <x-input-label for="end_date" :value="__('End Date (Optional)')" />
                                    <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                                                  :value="old('end_date')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                                </div>
                                <div>
                                    <x-input-label for="reward_points" :value="__('XP Reward on Completion')" />
                                    <x-text-input id="reward_points" name="reward_points" type="number" class="mt-1 block w-full"
                                                  :value="old('reward_points', 100)" min="0" required />
                                    <p class="mt-1 text-xs text-gray-500">XP awarded when all targets are completed.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('reward_points')" />
                                </div>
                            </div>
                        </div>

                        {{-- Levels --}}
                        <div class="space-y-4" id="targets-section">
                            <div class="flex justify-between items-center border-b pb-2">
                                <h3 class="text-base font-semibold text-gray-900">Campaign Levels</h3>
                                <button type="button" onclick="addTarget()"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-md hover:bg-indigo-100 transition">
                                    + Add Level
                                </button>
                            </div>

                            <p class="text-sm text-gray-500">Define the sequential levels participants must complete. Each level requires a target.</p>

                            @if($errors->has('targets'))
                                <div class="p-3 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                                    Please add at least one valid target.
                                </div>
                            @endif

                            <div id="targets-container" class="space-y-3">
                                {{-- JS will populate or pre-fill from old() --}}
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t">
                            <x-primary-button>Create Campaign</x-primary-button>
                            <a href="{{ route('business.gamification.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Target Row Template --}}
    <template id="target-template">
        <div class="target-row flex flex-wrap gap-3 items-end p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="w-12 text-center shrink-0 mb-2">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm level-number">1</span>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Level Icon</label>
                <select name="targets[INDEX][icon]" required
                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    @foreach($icons as $class => $label)
                        <option value="{{ $class }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Target Type</label>
                <select name="targets[INDEX][type]" required onchange="toggleProductSelect(this)"
                    class="target-type-select block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    @foreach($targetTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="product-select-container flex-1 min-w-[160px]" style="display: none;">
                <label class="block text-xs font-medium text-gray-600 mb-1">Specific Product</label>
                <select name="targets[INDEX][product_id]"
                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    <option value="">Any Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[160px] custom-label-wrapper">
                <label class="block text-xs font-medium text-gray-600 mb-1">Custom Label <span class="text-gray-400">(optional)</span></label>
                <input type="text" name="targets[INDEX][label]" placeholder="e.g. Buy any product"
                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" />
            </div>
            <div class="w-28">
                <label class="block text-xs font-medium text-gray-600 mb-1">Target Value</label>
                <input type="number" name="targets[INDEX][value]" value="1" min="1" required
                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" />
            </div>
            <div class="flex-none">
                <button type="button" onclick="removeTarget(this)"
                    class="text-red-500 hover:text-red-700 text-sm font-medium px-2 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <script>
        let targetIndex = 0;

        function previewLogo(input) {
            const preview = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleProductSelect(selectElement) {
            const targetRow = selectElement.closest('.target-row');
            const productContainer = targetRow.querySelector('.product-select-container');
            const labelWrapper = targetRow.querySelector('.custom-label-wrapper');
            
            if (selectElement.value === 'purchase') {
                productContainer.style.display = 'block';
                // HIDE custom label for purchase targets
                labelWrapper.style.display = 'none';
                labelWrapper.querySelector('input').value = '';
            } else {
                productContainer.style.display = 'none';
                // SHOW custom label for other target types
                labelWrapper.style.display = '';
                // Reset product select value
                productContainer.querySelector('select').value = '';
            }
        }

        function addTarget(data = {}) {
            const template = document.getElementById('target-template');
            const container = document.getElementById('targets-container');
            const clone = template.content.cloneNode(true);
            
            // Replace INDEX with targetIndex
            const html = clone.firstElementChild.outerHTML.replace(/INDEX/g, targetIndex);
            
            // Create a temporary wrapper to convert html string to DOM node
            const temp = document.createElement('div');
            temp.innerHTML = html;
            const newRow = temp.firstElementChild;
            
            if (data.icon) newRow.querySelector(`select[name="targets[${targetIndex}][icon]"]`).value = data.icon;
            if (data.type) {
                const typeSelect = newRow.querySelector(`select[name="targets[${targetIndex}][type]"]`);
                typeSelect.value = data.type;
                toggleProductSelect(typeSelect);
            }
            if (data.product_id) newRow.querySelector(`select[name="targets[${targetIndex}][product_id]"]`).value = data.product_id;
            if (data.label) newRow.querySelector(`input[name="targets[${targetIndex}][label]"]`).value = data.label;
            if (data.value) newRow.querySelector(`input[name="targets[${targetIndex}][value]"]`).value = data.value;

            container.appendChild(newRow);
            
            // Trigger toggle on load for default value if no data provided
            if (!data.type) {
                const typeSelect = newRow.querySelector(`select[name="targets[${targetIndex}][type]"]`);
                toggleProductSelect(typeSelect);
            }

            targetIndex++;
            updateLevelNumbers();
        }

        function removeTarget(btn) {
            btn.closest('.target-row').remove();
            updateLevelNumbers();
        }

        function updateLevelNumbers() {
            document.querySelectorAll('#targets-container .level-number').forEach((el, idx) => {
                el.textContent = idx + 1;
            });
        }

        // Auto-add one row if none exist (and no old input)
        document.addEventListener('DOMContentLoaded', () => {
            @if(old('targets'))
                @foreach(old('targets', []) as $i => $t)
                    addTarget({ type: '{{ $t['type'] ?? '' }}', icon: '{{ $t['icon'] ?? '' }}', label: '{{ $t['label'] ?? '' }}', value: '{{ $t['value'] ?? 1 }}' });
                @endforeach
            @else
                addTarget();
            @endif
        });
    </script>
</x-app-layout>
