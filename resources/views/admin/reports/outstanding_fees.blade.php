<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Outstanding Fees Report') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Track unpaid student fees and overdue payments.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-red-50 rounded-2xl p-6 border border-red-100 flex flex-col justify-between h-full">
                    <div>
                        <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Total Outstanding</p>
                        <h3 class="text-3xl font-extrabold text-red-700">LKR {{ number_format($totalOutstanding, 2) }}</h3>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $invoices->count() }} Pending Invoices
                        </span>
                    </div>
                </div>

                <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter Results
                    </h4>
                    <form method="GET" action="{{ route('finance.reports.outstanding') }}" class="flex flex-col md:flex-row gap-4 items-end">

                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Grade</label>
                            <select name="grade_id" id="grade_filter" onchange="filterSections()" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2">
                                <option value="">All Grades</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Section</label>
                            <select name="section_id" id="section_filter" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2">
                                <option value="">All Sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                            data-grade-id="{{ $section->grade_id }}"
                                            {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->grade->name }} - {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="submit" class="flex-1 md:flex-none px-5 py-2 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-sm">
                                Apply
                            </button>

                            @if(request()->anyFilled(['grade_id', 'section_id']))
                                <a href="{{ route('finance.reports.outstanding') }}" class="px-3 py-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition flex items-center justify-center" title="Clear Filters">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif

                            <a href="{{ route('finance.reports.outstanding.pdf', request()->all()) }}" target="_blank" class="px-3 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition flex items-center justify-center" title="Export PDF">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Outstanding Invoices</h3>
                    <span class="text-xs text-gray-500">Sorted by Due Date (Oldest First)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-bold tracking-wider text-left">
                                <th class="px-6 py-3">Student Details</th>
                                <th class="px-6 py-3">Invoice Info</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Balance Due</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($invoices as $invoice)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                                {{ substr($invoice->student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $invoice->student->name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $invoice->id }}</div>
                                        <div class="text-xs text-gray-500">Due: {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($invoice->due_date < now()->format('Y-m-d'))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Overdue {{ \Carbon\Carbon::parse($invoice->due_date)->diffInDays(now()) }} days
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-gray-900 block">LKR {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
                                        <span class="text-xs text-gray-400">Total: {{ number_format($invoice->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('finance.invoices.show', $invoice->id) }}" target="_blank"
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors inline-block">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-green-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-base font-medium text-gray-900">No outstanding invoices found</p>
                                            <p class="text-sm text-gray-500">All payments are up to date for this selection.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        function filterSections() {
            var selectedGradeId = document.getElementById('grade_filter').value;
            var sectionSelect = document.getElementById('section_filter');
            var options = sectionSelect.options;

            if (selectedGradeId !== "") {
                 // Optional: sectionSelect.value = "";
            }

            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var sectionGradeId = option.getAttribute('data-grade-id');

                if (option.value === "") continue;

                if (selectedGradeId === "" || sectionGradeId == selectedGradeId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            filterSections();
            var oldSection = "{{ request('section_id') }}";
            if(oldSection) {
                document.getElementById('section_filter').value = oldSection;
            }
        });
    </script>

</x-app-layout>
