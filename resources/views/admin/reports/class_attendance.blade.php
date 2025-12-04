<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Class Monthly Attendance Register') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('attendance.reports.attendance.class') }}">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

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

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Class (Section)</label>
                                <select name="section_id" id="section_filter" class="w-full rounded-md shadow-sm border-gray-300 text-sm" required>
                                    <option value="">-- Select Class --</option>
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
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Month</label>
                                <select name="month" class="w-full rounded-md shadow-sm border-gray-300 text-sm" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Year</label>
                                <input type="number" name="year" value="{{ request('year', now()->year) }}" class="w-full rounded-md shadow-sm border-gray-300 text-sm" required>
                            </div>

                        </div>

                        <div class="mt-4 flex justify-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-md text-sm font-bold hover:bg-blue-900">
                                View
                            </button>

                            @if(request()->filled('section_id'))
                                <a href="{{ route('finance.reports.attendance.class.pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-bold hover:bg-red-700 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    PDF
                                </a>
                            @endif
                        </div>
                    </form>
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
                });
            </script>

            @if($selectedSection && $students->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-bold">
                            Class: {{ $selectedSection->grade->name }} - {{ $selectedSection->name }} |
                            Month: {{ \Carbon\Carbon::create(request('year'), request('month'), 1)->format('F Y') }}
                        </h3>
                        <span class="text-sm text-gray-500">P=Present, A=Absent, L=Late</span>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-3 text-left font-medium text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-100 border-r z-10 w-48">
                                        Student Name
                                    </th>

                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        <th class="px-1 py-3 text-center font-medium text-gray-700 border-r w-8">
                                            {{ $day }}
                                        </th>
                                    @endfor

                                    <th class="px-2 py-3 text-center font-medium text-green-700 uppercase tracking-wider border-l border-gray-300">P</th>
                                    <th class="px-2 py-3 text-center font-medium text-red-700 uppercase tracking-wider">A</th>
                                    <th class="px-2 py-3 text-center font-medium text-yellow-700 uppercase tracking-wider">L</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($students as $student)
                                    @php
                                        $presentCount = 0;
                                        $absentCount = 0;
                                        $lateCount = 0; // Initialize Late count
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-2 whitespace-nowrap font-medium text-gray-900 sticky left-0 bg-white border-r z-10">
                                            {{ $student->name }}
                                        </td>

                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $date = \Carbon\Carbon::create(request('year'), request('month'), $day)->format('Y-m-d');
                                                $status = $attendanceMatrix[$student->id][$date] ?? null;

                                                // Count increment logic
                                                if($status == 'present') $presentCount++;
                                                if($status == 'absent') $absentCount++;
                                                if($status == 'late') $lateCount++;
                                            @endphp

                                            <td class="px-1 py-2 text-center border-r">
                                                @if ($status == 'present')
                                                    <span class="text-green-600 font-bold">P</span>
                                                @elseif ($status == 'absent')
                                                    <span class="text-red-600 font-bold">A</span>
                                                @elseif ($status == 'late')
                                                    <span class="text-yellow-600 font-bold">L</span>
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                        @endfor

                                        <td class="px-2 py-2 text-center font-bold text-green-700 bg-green-50 border-l border-gray-300">{{ $presentCount }}</td>
                                        <td class="px-2 py-2 text-center font-bold text-red-700 bg-red-50">{{ $absentCount }}</td>
                                        <td class="px-2 py-2 text-center font-bold text-yellow-700 bg-yellow-50">{{ $lateCount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @elseif(request()->filled('section_id'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        No students found in this class.
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
