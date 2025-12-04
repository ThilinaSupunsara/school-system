<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <a href="{{ route('finance.invoices.generate.form') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Generate New Invoices') }}
                        </a>
                    </div>

                    <form method="GET" action="{{ route('finance.invoices.index') }}" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Student</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Adm No..." class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade</label>
                                <select name="grade_id" id="grade_filter" onchange="filterSections()" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                                    <option value="">All Grades</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section</label>
                                <select name="section_id" id="section_filter" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
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

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                <select name="status" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label>
                                <input type="date" name="due_date" value="{{ request('due_date') }}" class="w-full rounded-md shadow-sm border-gray-300 text-sm">
                            </div>

                        </div>

                        <div class="mt-4 flex justify-end space-x-2">
                            <a href="{{ route('finance.invoices.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400 font-bold">
                                Reset
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 font-bold">
                                Apply Filters
                            </button>
                            <a href="{{ route('finance.invoices.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 font-bold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </a>
                        </div>
                    </form>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inv #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $invoice->student->name }} <br>
                                            <span class="text-xs text-gray-500">{{ $invoice->student->admission_no }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">
                                            {{ number_format($invoice->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($invoice->status == 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                            @elseif($invoice->status == 'partial')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Partial</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $invoice->due_date < now() && $invoice->status != 'paid' ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                            {{ $invoice->due_date }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('finance.invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                            <form class="inline" method="POST" action="{{ route('finance.invoices.destroy', $invoice->id) }}" onsubmit="return confirm('Delete this invoice?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No invoices found matching your criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $invoices->links() }}
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

            if (selectedGradeId !== "") {
                 sectionSelect.value = "";
            }

            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var sectionGradeId = option.getAttribute('data-grade-id');

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
            var oldSection = "{{ request('section_id') }}";
            if(oldSection) {
                document.getElementById('section_filter').value = oldSection;
            }
        });
    </script>

</x-app-layout>
