<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Monthly Attendance Report') }}
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 එක Laravel styles එක්ක ගැලපෙන්න පොඩි fix එකක් */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border-color: #d1d5db !important; /* gray-300 */
            display: flex;
            align-items: center;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('attendance.reports.attendance.student') }}" id="report-form">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 pb-4 border-b">
                            <div>
                                <label class="block text-xs text-gray-500 font-bold uppercase mb-1">Filter by Grade</label>
                                <select name="grade_id" class="block w-full rounded-md shadow-sm border-gray-300 text-sm" onchange="this.form.submit()">
                                    <option value="">All Grades</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 font-bold uppercase mb-1">Filter by Section</label>
                                <select name="section_id" class="block w-full rounded-md shadow-sm border-gray-300 text-sm" onchange="this.form.submit()">
                                    <option value="">All Sections</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('attendance.reports.attendance.student') }}" class="text-sm text-blue-600 hover:underline mb-2">Clear Filters</a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <label for="student_id" class="block font-medium text-sm text-gray-700">{{ __('Select Student') }}</label>
                                <select name="student_id" id="student_id" class="block w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">Type to search student...</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->admission_no }}) - {{ $student->section->grade->name }} {{ $student->section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="month" class="block font-medium text-sm text-gray-700">{{ __('Month') }}</label>
                                <select name="month" id="month" class="block w-full rounded-md shadow-sm border-gray-300" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex items-end gap-4">
                                <div class="w-1/2">
                                    <label for="year" class="block font-medium text-sm text-gray-700">{{ __('Year') }}</label>
                                    <input id="year" class="block w-full rounded-md shadow-sm border-gray-300"
                                           type="number" name="year" value="{{ request('year', now()->year) }}" required />
                                </div>
                                <button type="submit" class="w-1/2 inline-flex justify-center items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 h-10">
                                    {{ __('Generate') }}
                                </button>
                                @if(request()->filled('student_id'))
                                <a href="{{ route('finance.reports.attendance.student.pdf', request()->all()) }}" target="_blank" class="inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 h-10">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    PDF
                                </a>
                            @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            @if($selectedStudent)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $selectedStudent->name }}</h3>
                            <p class="text-gray-600">Admission No: {{ $selectedStudent->admission_no }} | Class: {{ $selectedStudent->section->grade->name }} - {{ $selectedStudent->section->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Attendance Percentage</p>
                            <p class="text-3xl font-bold {{ $summary['percentage'] < 80 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $summary['percentage'] }}%
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-green-100 p-4 rounded text-center">
                            <span class="block text-green-800 font-bold text-xl">{{ $summary['present'] }}</span>
                            <span class="text-green-600 text-sm">Days Present</span>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded text-center">
                            <span class="block text-yellow-800 font-bold text-xl">{{ $summary['late'] }}</span>
                            <span class="text-yellow-600 text-sm">Days Late</span>
                        </div>
                        <div class="bg-red-100 p-4 rounded text-center">
                            <span class="block text-red-800 font-bold text-xl">{{ $summary['absent'] }}</span>
                            <span class="text-red-600 text-sm">Days Absent</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/4">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/4">Day</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $dateString = \Carbon\Carbon::create(request('year'), request('month'), $day)->format('Y-m-d');
                                        $dateObj = \Carbon\Carbon::parse($dateString);
                                        $isWeekend = $dateObj->isWeekend();
                                        $record = $attendanceRecords->get($dateString);
                                    @endphp

                                    <tr class="{{ $isWeekend ? 'bg-gray-100' : '' }}">
                                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $dateString }}</td>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ $dateObj->format('l') }}</td>
                                        <td class="px-6 py-2 whitespace-nowrap text-center">
                                            @if ($record)
                                                @if ($record->status == 'present')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                                @elseif ($record->status == 'absent')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                                @elseif ($record->status == 'late')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                                @endif
                                            @else
                                                @if ($isWeekend)
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @else
                                                    <span class="text-gray-300 text-xs italic">Not Marked</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Student Dropdown එකට Select2 apply කිරීම
            $('#student_id').select2({
                placeholder: "Type name or admission no...",
                allowClear: true,
                width: '100%' // Dropdown width fix
            });
        });
    </script>

</x-app-layout>
