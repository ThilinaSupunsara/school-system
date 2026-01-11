<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Monthly Salary Sheet') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Review staff payroll details for a specific period.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row items-end gap-4 justify-between">
                <form method="GET" action="{{ route('finance.reports.salary_sheet') }}" class="w-full md:w-auto flex flex-col md:flex-row items-end gap-4">

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Month</label>
                        <select name="month" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="w-full md:w-32">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Year</label>
                        <input type="number" name="year" value="{{ $selectedYear }}"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                    </div>

                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition shadow-md">
                        Generate Sheet
                    </button>
                </form>

                <a href="{{ route('finance.reports.salary_sheet.pdf', request()->all()) }}" target="_blank"
                   class="w-full md:w-auto flex items-center justify-center px-5 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition font-bold text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Gross Salary</p>
                    <p class="text-2xl font-bold text-blue-800">
                        LKR {{ number_format($totals['basic'] + $totals['allowances'], 2) }}
                    </p>
                    <div class="mt-2 text-xs text-blue-600 flex justify-between">
                        <span>Basic: {{ number_format($totals['basic'], 2) }}</span>
                        <span>+ Allowances: {{ number_format($totals['allowances'], 2) }}</span>
                    </div>
                </div>

                <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                    <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Total Deductions</p>
                    <p class="text-2xl font-bold text-red-700">
                        LKR {{ number_format($totals['deductions'], 2) }}
                    </p>
                    <p class="mt-2 text-xs text-red-500">Includes EPF, taxes, and other penalties.</p>
                </div>

                <div class="bg-green-50 rounded-2xl p-6 border border-green-100">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Net Payout</p>
                    <p class="text-3xl font-bold text-green-800">
                        LKR {{ number_format($totals['net'], 2) }}
                    </p>
                    <p class="mt-2 text-xs text-green-600">Total payable amount for this month.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-900">Payroll Records</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Period: {{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }}, {{ $selectedYear }}
                        </p>
                    </div>
                    <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-1 rounded-full">{{ $payrolls->count() }} Records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Staff Details</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Basic Salary</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Allowances</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-red-500 uppercase tracking-wider">Deductions</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-green-600 uppercase tracking-wider">Net Salary</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($payrolls as $payroll)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">{{ $payroll->staff->user->name }}</span>
                                            <span class="text-xs text-gray-500">ID: {{ $payroll->staff_id }} | {{ $payroll->staff->designation }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                                        {{ number_format($payroll->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                                        {{ number_format($payroll->total_allowances, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-red-600 font-medium">
                                        {{ number_format($payroll->total_deductions, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-green-700 bg-green-50 px-2 py-1 rounded border border-green-100">
                                            {{ number_format($payroll->net_salary, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-base font-medium text-gray-900">No payroll records found</p>
                                            <p class="text-sm text-gray-500">Try selecting a different month or year.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td class="px-6 py-4 text-left font-bold text-gray-900 uppercase text-sm">Grand Totals</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 text-sm">{{ number_format($totals['basic'], 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 text-sm">{{ number_format($totals['allowances'], 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-red-600 text-sm">{{ number_format($totals['deductions'], 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-green-700 text-base">{{ number_format($totals['net'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
