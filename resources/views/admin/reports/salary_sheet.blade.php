<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monthly Salary Sheet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Report</h3>
                    <form method="GET" action="{{ route('finance.reports.salary_sheet') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <label for="month" class="block font-medium text-sm text-gray-700">{{ __('Month') }}</label>
                                <select name="month" id="month" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="year" class="block font-medium text-sm text-gray-700">{{ __('Year') }}</label>
                                <input id="year" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="number"
                                       name="year"
                                       value="{{ $selectedYear }}"  required />
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    {{ __('Generate') }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Salary Sheet for: {{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }}, {{ $selectedYear }}
                        </h3>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Name</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Allowances</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Deductions</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net Salary</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($payrolls as $payroll)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->staff_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->staff->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($payroll->total_allowances, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-red-600">{{ number_format($payroll->total_deductions, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold">{{ number_format($payroll->net_salary, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No payroll records found for this month/year.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                <tr class="font-bold">
                                    <td colspan="2" class="px-6 py-3 text-right uppercase">Total:</td>
                                    <td class="px-6 py-3 text-right">{{ number_format($totals['basic'], 2) }}</td>
                                    <td class="px-6 py-3 text-right">{{ number_format($totals['allowances'], 2) }}</td>
                                    <td class="px-6 py-3 text-right text-red-600">{{ number_format($totals['deductions'], 2) }}</td>
                                    <td class="px-6 py-3 text-right">{{ number_format($totals['net'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
