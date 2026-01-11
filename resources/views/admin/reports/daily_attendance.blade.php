<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Daily Attendance') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Overview of student attendance for a specific day.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row items-end gap-4 justify-between">
                <form method="GET" action="{{ route('finance.reports.attendance.daily') }}" class="w-full md:w-auto flex flex-col md:flex-row items-end gap-4">
                    <div class="w-full md:w-64">
                        <label for="date" class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Select Date</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate }}" required
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                    </div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition shadow-md">
                        Filter Report
                    </button>
                </form>

                <a href="{{ route('finance.reports.attendance.daily.pdf', ['date' => $selectedDate]) }}" target="_blank"
                   class="w-full md:w-auto flex items-center justify-center px-5 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition font-bold text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Students</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $reportData->sum('total') }}</p>
                </div>

                <div class="bg-green-50 p-5 rounded-2xl border border-green-100">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Present</p>
                    <div class="flex items-end gap-2 mt-1">
                        <p class="text-2xl font-bold text-green-700">{{ $reportData->sum('present') }}</p>
                        @if($reportData->sum('total') > 0)
                            <span class="text-xs font-bold text-green-600 mb-1">
                                ({{ round(($reportData->sum('present') / $reportData->sum('total')) * 100) }}%)
                            </span>
                        @endif
                    </div>
                </div>

                <div class="bg-yellow-50 p-5 rounded-2xl border border-yellow-100">
                    <p class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Late</p>
                    <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $reportData->sum('late') }}</p>
                </div>

                <div class="bg-red-50 p-5 rounded-2xl border border-red-100">
                    <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Absent</p>
                    <p class="text-2xl font-bold text-red-700 mt-1">{{ $reportData->sum('absent') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Class-wise Breakdown</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Date: {{ \Carbon\Carbon::parse($selectedDate)->format('l, F d, Y') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-bold tracking-wider text-center">
                                <th class="px-6 py-3 text-left">Class</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3 text-green-600">Present</th>
                                <th class="px-6 py-3 text-yellow-600">Late</th>
                                <th class="px-6 py-3 text-red-600">Absent</th>
                                <th class="px-6 py-3 text-gray-400">Pending</th>
                                <th class="px-6 py-3 text-right">Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($reportData as $row)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-left">
                                        {{ $row->grade }} - {{ $row->section }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-600">
                                        {{ $row->total }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-green-600 bg-green-50/30">
                                        {{ $row->present }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-yellow-600 bg-yellow-50/30">
                                        {{ $row->late }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-red-600 bg-red-50/30">
                                        {{ $row->absent }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-400">
                                        @if($row->not_marked > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $row->not_marked }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-700">
                                        {{ $row->percentage }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold text-sm text-gray-800 border-t border-gray-200">
                            <tr>
                                <td class="px-6 py-4 text-left">GRAND TOTAL</td>
                                <td class="px-6 py-4 text-center">{{ $reportData->sum('total') }}</td>
                                <td class="px-6 py-4 text-center text-green-700">{{ $reportData->sum('present') }}</td>
                                <td class="px-6 py-4 text-center text-yellow-700">{{ $reportData->sum('late') }}</td>
                                <td class="px-6 py-4 text-center text-red-700">{{ $reportData->sum('absent') }}</td>
                                <td class="px-6 py-4 text-center text-gray-500">{{ $reportData->sum('not_marked') }}</td>
                                <td class="px-6 py-4 text-right">
                                    @php
                                        $grandTotal = $reportData->sum('total');
                                        $grandPresent = $reportData->sum('present') + $reportData->sum('late');
                                        $grandPercentage = $grandTotal > 0 ? round(($grandPresent / $grandTotal) * 100, 1) : 0;
                                    @endphp
                                    {{ $grandPercentage }}%
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
