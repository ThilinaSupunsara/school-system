<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Attendance Register') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Monthly class-wise attendance report.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('finance.reports.attendance.class') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Grade</label>
                            <select name="grade_id" id="grade_filter" onchange="filterSections()"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                <option value="">All Grades</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Class (Section)</label>
                            <select name="section_id" id="section_filter" required
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
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
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Month</label>
                            <select name="month" required
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Year</label>
                            <input type="number" name="year" value="{{ request('year', now()->year) }}" required
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-md">
                                Generate
                            </button>

                            @if(request()->filled('section_id'))
                                <a href="{{ route('finance.reports.attendance.class.pdf', request()->all()) }}" target="_blank"
                                   class="px-3 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition" title="Export PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                            @endif
                        </div>

                    </div>
                </form>
            </div>

            @if($selectedSection && $students->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $selectedSection->grade->name }} - {{ $selectedSection->name }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                Attendance for <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::create(request('year'), request('month'), 1)->format('F Y') }}</span>
                            </p>
                        </div>
                        <div class="flex gap-4 text-xs font-bold uppercase tracking-wider">
                            <div class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-1.5"></span> Present</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1.5"></span> Absent</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-1.5"></span> Late</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto relative">
                        <table class="min-w-full text-xs text-left border-collapse">
                            <thead class="text-gray-500 bg-gray-50 border-b border-gray-200 uppercase font-medium">
                                <tr>
                                    <th class="px-4 py-3 sticky left-0 z-20 bg-gray-50 border-r border-gray-200 w-60 shadow-[4px_0_10px_-4px_rgba(0,0,0,0.1)]">
                                        Student Name
                                    </th>

                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        <th class="px-1 py-3 text-center border-r border-gray-100 w-8 min-w-[2rem]">
                                            {{ $day }}
                                        </th>
                                    @endfor

                                    <th class="px-2 py-3 text-center bg-green-50 text-green-700 border-l border-green-100 font-bold">P</th>
                                    <th class="px-2 py-3 text-center bg-red-50 text-red-700 border-l border-red-100 font-bold">A</th>
                                    <th class="px-2 py-3 text-center bg-yellow-50 text-yellow-700 border-l border-yellow-100 font-bold">L</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @foreach ($students as $student)
                                    @php
                                        $presentCount = 0;
                                        $absentCount = 0;
                                        $lateCount = 0;
                                    @endphp
                                    <tr class="hover:bg-blue-50/50 transition-colors">

                                        <td class="px-4 py-2 font-semibold text-gray-900 sticky left-0 z-20 bg-white border-r border-gray-200 shadow-[4px_0_10px_-4px_rgba(0,0,0,0.05)] whitespace-nowrap">
                                            {{ $student->name }}
                                        </td>

                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $date = \Carbon\Carbon::create(request('year'), request('month'), $day)->format('Y-m-d');
                                                $status = $attendanceMatrix[$student->id][$date] ?? null;

                                                if($status == 'present') $presentCount++;
                                                if($status == 'absent') $absentCount++;
                                                if($status == 'late') $lateCount++;
                                            @endphp

                                            <td class="px-1 py-2 text-center border-r border-gray-100">
                                                @if ($status == 'present')
                                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full" title="Present"></span>
                                                @elseif ($status == 'absent')
                                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full" title="Absent"></span>
                                                @elseif ($status == 'late')
                                                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full" title="Late"></span>
                                                @else
                                                    <span class="text-gray-200 text-[10px]">&bull;</span>
                                                @endif
                                            </td>
                                        @endfor

                                        <td class="px-2 py-2 text-center bg-green-50 text-green-800 font-bold border-l border-green-100">{{ $presentCount }}</td>
                                        <td class="px-2 py-2 text-center bg-red-50 text-red-800 font-bold border-l border-red-100">{{ $absentCount }}</td>
                                        <td class="px-2 py-2 text-center bg-yellow-50 text-yellow-800 font-bold border-l border-yellow-100">{{ $lateCount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(request()->filled('section_id'))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-900">No Students Found</h3>
                    <p class="text-gray-500">There are no students assigned to this class section yet.</p>
                </div>
            @endif

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

</x-app-layout>
