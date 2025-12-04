<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Attendance Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('attendance.reports.attendance.daily') }}" class="flex items-end gap-4">
                        <div>
                            <label for="date" class="block font-medium text-sm text-gray-700">{{ __('Select Date') }}</label>
                            <input id="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                   type="date"
                                   name="date"
                                   value="{{ $selectedDate }}"
                                   required />
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 text-white h-10">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('finance.reports.attendance.daily.pdf', ['date' => $selectedDate]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 h-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            PDF
                        </a>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 text-center">
                        Attendance Summary for: {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Students</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-green-600 uppercase tracking-wider">Present</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-yellow-600 uppercase tracking-wider">Late</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-red-600 uppercase tracking-wider">Absent</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Not Marked</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reportData as $row)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            {{ $row->grade }} - {{ $row->section }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center font-bold">{{ $row->total }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-green-600 bg-green-50 font-bold">{{ $row->present }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-yellow-600 bg-yellow-50 font-bold">{{ $row->late }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-red-600 bg-red-50 font-bold">{{ $row->absent }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-400">
                                            @if($row->not_marked > 0)
                                                <span class="text-red-500 font-bold" title="Attendance not fully marked!">{{ $row->not_marked }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold">
                                            {{ $row->percentage }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100 font-bold">
                                <tr>
                                    <td class="px-6 py-3">TOTALS</td>
                                    <td class="px-6 py-3 text-center">{{ $reportData->sum('total') }}</td>
                                    <td class="px-6 py-3 text-center text-green-700">{{ $reportData->sum('present') }}</td>
                                    <td class="px-6 py-3 text-center text-yellow-700">{{ $reportData->sum('late') }}</td>
                                    <td class="px-6 py-3 text-center text-red-700">{{ $reportData->sum('absent') }}</td>
                                    <td class="px-6 py-3 text-center text-gray-500">{{ $reportData->sum('not_marked') }}</td>
                                    <td class="px-6 py-3 text-right">
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
    </div>
</x-app-layout>
