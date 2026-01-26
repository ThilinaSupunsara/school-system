<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="p-2 rounded-full bg-white text-gray-600 shadow-sm border border-gray-200 hover:bg-gray-50 hover:text-blue-600 transition-all duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Generate Student Invoices') }}
                </h2>
                <p class="text-sm text-gray-500">Create bulk invoices for specific grades or sections.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Generation Failed</h3>
                        <div class="mt-2 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-100">

                <div class="px-8 py-6 border-b border-gray-100 bg-blue-50/50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </span>
                        Invoice Details
                    </h3>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('finance.invoices.generate.process') }}">
                        @csrf

                        <div class="space-y-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label for="grade_filter" class="block text-sm font-bold text-gray-700 mb-2">Target Grade</label>
                                    <div class="relative">
                                        <select name="grade_id" id="grade_filter" onchange="filterSections()" required
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none">
                                            <option value="">Select a Grade</option>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                                    {{ $grade->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>

                                <div>
                                    <label for="section_filter" class="block text-sm font-bold text-gray-700 mb-2">
                                        Target Section <span class="text-gray-400 font-normal">(Optional)</span>
                                    </label>
                                    <div class="relative">
                                        <select name="section_id" id="section_filter"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none">
                                            <option value="">All Sections in Grade</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                        data-grade-id="{{ $section->grade_id }}"
                                                        {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 ml-1">Leave empty to generate for the entire grade.</p>
                                </div>
                            </div>

                            <div>
                                <label for="fee_category_id" class="block text-sm font-bold text-gray-700 mb-2">Fee Category</label>
                                <div class="relative">
                                    <select name="fee_category_id" id="fee_category_id" required
                                            class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none">
                                        <option value="">Select Fee Type</option>
                                        @foreach ($feeCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('fee_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="invoice_date" class="block text-sm font-bold text-gray-700 mb-2">Invoice Date</label>
                                    <input id="invoice_date" type="date" name="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3">
                                </div>
                                <div>
                                    <label for="due_date" class="block text-sm font-bold text-gray-700 mb-2">Due Date</label>
                                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>

                            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                                <a href="{{ route('finance.invoices.index') }}" class="mr-4 text-sm font-medium text-gray-600 hover:text-gray-900">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Generate Invoices
                                </button>
                            </div>

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

            // Reset selection when grade changes
            if (selectedGradeId !== "") {
                 sectionSelect.value = "";
            }

            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var sectionGradeId = option.getAttribute('data-grade-id');

                // Always show 'All Sections' option
                if (option.value === "") {
                    continue;
                }

                if (selectedGradeId === "" || sectionGradeId == selectedGradeId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            filterSections();
        });
    </script>

</x-app-layout>
