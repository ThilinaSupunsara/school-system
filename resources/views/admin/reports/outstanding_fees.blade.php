<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Outstanding Fees Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Report</h3>
                    <form method="GET" action="{{ route('finance.reports.outstanding') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <label for="grade_id" class="block font-medium text-sm text-gray-700">{{ __('Filter by Grade') }}</label>
                                <select name="grade_id" id="grade_filter" onchange="filterSections()" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                    <option value="">{{ __('All Grades') }}</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="section_id" class="block font-medium text-sm text-gray-700">{{ __('Filter by Section') }}</label>
                                <select name="section_id" id="section_filter" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                    <option value="">{{ __('All Sections') }}</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                                data-grade-id="{{ $section->grade_id }}"
                                                {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end space-x-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 text-white h-10">
                                    {{ __('Generate') }}
                                </button>
                                <a href="{{ route('finance.reports.outstanding') }}" class="text-sm text-gray-600 hover:text-gray-900 self-center">
                                    {{ __('Clear Filters') }}
                                </a>
                                <a href="{{ route('finance.reports.outstanding.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 h-10 ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download PDF
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-800 rounded">
                        <p class="text-xl font-bold">
                            Total Outstanding: LKR {{ number_format($totalOutstanding, 2) }}
                        </p>
                        <p>Found {{ $invoices->count() }} pending invoices matching your filters.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inv #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance Due (LKR)</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($invoices as $invoice)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $invoice->due_date }}
                                            @if($invoice->due_date < now()->format('Y-m-d') && $invoice->status != 'paid')
                                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Overdue
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">
                                            {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('finance.invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank">
                                                View Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No outstanding invoices found matching your filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function filterSections() {
            var selectedGradeId = document.getElementById('grade_filter').value;
            var sectionSelect = document.getElementById('section_filter');
            var options = sectionSelect.options;

            // Reset selection only if user manually changes grade
            if (selectedGradeId !== "") {
                 // sectionSelect.value = ""; // Optional: Keep this if you want to clear section on grade change
            }

            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var sectionGradeId = option.getAttribute('data-grade-id');

                if (option.value === "") {
                    continue; // Keep "All Sections" visible
                }

                if (selectedGradeId === "" || sectionGradeId == selectedGradeId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        }

        // Page load එකේදී run වෙන්න (පරණ filter එක රැකගන්න)
        document.addEventListener("DOMContentLoaded", function() {
            filterSections();
            var oldSection = "{{ request('section_id') }}";
            if(oldSection) {
                document.getElementById('section_filter').value = oldSection;
            }
        });
    </script>

</x-app-layout>
