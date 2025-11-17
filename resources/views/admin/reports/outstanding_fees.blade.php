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
                                <select name="grade_id" id="grade_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
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
                                <select name="section_id" id="section_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                    <option value="">{{ __('All Sections') }}</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end space-x-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    {{ __('Generate') }}
                                </button>
                                <a href="{{ route('finance.reports.outstanding') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                    {{ __('Clear Filters') }}
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
</x-app-layout>
