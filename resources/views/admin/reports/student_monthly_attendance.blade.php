<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Student Monthly Report') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Detailed attendance record for an individual student.</p>
            </div>
        </div>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important;
            border-color: #e5e7eb !important;
            border-radius: 0.75rem !important;
            display: flex;
            align-items: center;
            padding-left: 10px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            border-color: #e5e7eb !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('finance.reports.attendance.student') }}" id="report-form">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Grade</label>
                            <select name="grade_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" onchange="this.form.submit()">
                                <option value="">All Grades</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Section</label>
                            <select name="section_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" onchange="this.form.submit()">
                                <option value="">All Sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->grade->name }} - {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                        <div class="md:col-span-2">
                            <label for="student_id" class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Select Student</label>
                            <select name="student_id" id="student_id" class="w-full" required>
                                <option value="">Search by name or admission no...</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->admission_no }}) - {{ $student->section->grade->name }} {{ $student->section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Month</label>
                                <select name="month" id="month" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Year</label>
                                <input type="number" name="year" value="{{ request('year', now()->year) }}" required
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                            </div>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('finance.reports.attendance.student') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition">
                            Reset
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md">
                            Generate Report
                        </button>
                        @if(request()->filled('student_id'))
                            <a href="{{ route('finance.reports.attendance.student.pdf', request()->all()) }}" target="_blank"
                               class="px-5 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold text-sm hover:bg-red-100 transition flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($selectedStudent)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start border-b border-gray-100">

                        <div class="flex items-center gap-5 flex-1">
                            <div class="h-20 w-20 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold">
                                {{ substr($selectedStudent->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $selectedStudent->name }}</h3>
                                <div class="flex flex-wrap gap-3 mt-1 text-sm text-gray-500">
                                    <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                        {{ $selectedStudent->admission_no }}
                                    </span>
                                    <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $selectedStudent->section->grade->name }} - {{ $selectedStudent->section->name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center">
                                <svg class="w-20 h-20 transform -rotate-90">
                                    <circle class="text-gray-200" stroke-width="8" stroke="currentColor" fill="transparent" r="32" cx="40" cy="40"/>
                                    <circle class="{{ $summary['percentage'] < 80 ? 'text-red-500' : 'text-green-500' }}"
                                            stroke-width="8" stroke-dasharray="200" stroke-dashoffset="{{ 200 - (200 * $summary['percentage'] / 100) }}"
                                            stroke-linecap="round" stroke="currentColor" fill="transparent" r="32" cx="40" cy="40"/>
                                </svg>
                                <span class="absolute text-lg font-bold text-gray-700">{{ $summary['percentage'] }}%</span>
                            </div>
                            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-wide">Attendance Rate</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 divide-x divide-gray-100 bg-gray-50/50">
                        <div class="p-4 text-center">
                            <span class="block text-2xl font-bold text-green-600">{{ $summary['present'] }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Days Present</span>
                        </div>
                        <div class="p-4 text-center">
                            <span class="block text-2xl font-bold text-yellow-600">{{ $summary['late'] }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Days Late</span>
                        </div>
                        <div class="p-4 text-center">
                            <span class="block text-2xl font-bold text-red-600">{{ $summary['absent'] }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Days Absent</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Day</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $dateString = \Carbon\Carbon::create(request('year'), request('month'), $day)->format('Y-m-d');
                                        $dateObj = \Carbon\Carbon::parse($dateString);
                                        $isWeekend = $dateObj->isWeekend();
                                        $record = $attendanceRecords->get($dateString);
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition-colors {{ $isWeekend ? 'bg-gray-50/50' : '' }}">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($dateString)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $dateObj->format('l') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-center">
                                            @if ($record)
                                                @if ($record->status == 'present')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                        Present
                                                    </span>
                                                @elseif ($record->status == 'absent')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                        Absent
                                                    </span>
                                                @elseif ($record->status == 'late')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                                        Late
                                                    </span>
                                                @endif
                                            @else
                                                @if ($isWeekend)
                                                    <span class="text-xs text-gray-400 font-medium">Weekend</span>
                                                @else
                                                    <span class="text-xs text-gray-300 italic">Not Marked</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#student_id').select2({
                placeholder: "Type name or admission no...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

</x-app-layout>
