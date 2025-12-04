<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            {{ __('Generate Student Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('finance.invoices.generate.process') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="grade_id" class="block font-medium text-sm text-gray-700">{{ __('Grade') }}</label>
                            <select name="grade_id" id="grade_filter" onchange="filterSections()" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Grade') }}</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="section_id" class="block font-medium text-sm text-gray-700">{{ __('Section (Optional)') }}</label>
                            <select name="section_id" id="section_filter" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                <option value="">{{ __('All Sections in this Grade') }}</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                            data-grade-id="{{ $section->grade_id }}"
                                            {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Leave empty to generate invoices for the entire grade.</p>
                        </div>

                        <div class="mb-4">
                            <label for="fee_category_id" class="block font-medium text-sm text-gray-700">{{ __('Fee Category') }}</label>
                            <select name="fee_category_id" id="fee_category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Fee Category') }}</option>
                                @foreach ($feeCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('fee_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="invoice_date" class="block font-medium text-sm text-gray-700">{{ __('Invoice Date') }}</label>
                                <input id="invoice_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="date" name="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required />
                            </div>
                            <div>
                                <label for="due_date" class="block font-medium text-sm text-gray-700">{{ __('Due Date') }}</label>
                                <input id="due_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="date" name="due_date" value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Generate Invoices') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function filterSections() {
            var selectedGradeId = document.getElementById('grade_filter').value;
            var sectionSelect = document.getElementById('section_filter');
            var options = sectionSelect.options;

            // Reset selection
            if (selectedGradeId !== "") {
                 sectionSelect.value = "";
            }

            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var sectionGradeId = option.getAttribute('data-grade-id');

                // 'All Sections' option එක හැමවිටම පෙන්වන්න
                if (option.value === "") {
                    continue;
                }

                // Grade එක ගැලපෙනවා නම් පෙන්වන්න
                if (selectedGradeId === "" || sectionGradeId == selectedGradeId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        }

        // Page Load එකේදී run වෙන්න
        document.addEventListener("DOMContentLoaded", function() {
            filterSections();
        });
    </script>

</x-app-layout>
