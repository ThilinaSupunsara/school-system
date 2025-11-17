<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mark Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <h3 class="text-2xl font-bold">Class: {{ $section->grade->name }} - {{ $section->name }}</h3>
                        <p class="text-lg text-gray-600">Date: <span class="font-semibold">{{ $today->format('Y-m-d') }}</span></p>
                    </div>

                    <form method="POST" action="{{ route('teacher.attendance.mark.store', $section->id) }}">
                        @csrf
                        <input type="hidden" name="attendance_date" value="{{ $today->format('Y-m-d') }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Adm. No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-20 bg-gray-50 z-10">Student Name</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Absent</th>

                                        @foreach ($lastFiveSchoolDays as $date)
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $date->format('D') }} <br>
                                                {{ $date->format('M d') }}
                                            </th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Today's Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($students as $student)
                                        @php
                                            // 'absent' default කරනවා (අපි කලින් කතා කරගත්ත විදිහට)
                                            $checkedStatus = $attendanceData->get($student->id, 'absent');
                                            $absentCount = $monthlyAbsentCounts->get($student->id, 0);
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap sticky left-0 bg-white z-10">{{ $student->admission_no }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap sticky left-20 bg-white z-10">{{ $student->name }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                @if($absentCount > 0)
                                                    <span class="font-bold text-red-600">{{ $absentCount }} {{ $absentCount > 1 ? 'Days' : 'Day' }}</span>
                                                @else
                                                    <span class="text-gray-400">0</span>
                                                @endif
                                            </td>

                                            @foreach ($lastFiveSchoolDays as $date)
                                                @php
                                                    $dateString = $date->format('Y-m-d');
                                                    $status = $pastAttendance[$student->id][$dateString] ?? null;
                                                @endphp
                                                <td class="px-4 py-4 text-center">
                                                    @if ($status == 'present')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 font-bold">P</span>
                                                    @elseif ($status == 'absent')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800 font-bold">A</span>
                                                    @elseif ($status == 'late')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-800 font-bold">L</span>
                                                    @else
                                                        <span class="text-gray-400">-</span> @endif
                                                </td>
                                            @endforeach
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-4">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="form-radio text-green-600" {{ $checkedStatus == 'present' ? 'checked' : '' }}>
                                                        <span class="ml-2">Present</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="form-radio text-red-600" {{ $checkedStatus == 'absent' ? 'checked' : '' }}>
                                                        <span class="ml-2">Absent</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="form-radio text-yellow-600" {{ $checkedStatus == 'late' ? 'checked' : '' }}>
                                                        <span class="ml-2">Late</span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">No students found in this section.</td> </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($students->isNotEmpty())
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                                {{ __('Save Attendance') }}
                            </button>
                        </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
