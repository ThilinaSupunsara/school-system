<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.attendance.class_list') }}" class="p-2 rounded-full bg-white text-gray-600 shadow-sm border border-gray-200 hover:bg-gray-50 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Mark Attendance') }}
                </h2>
                <p class="text-sm text-gray-500">{{ $section->grade->name }} - {{ $section->name }} | {{ $today->format('l, M d, Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 flex items-center p-4 text-green-800 bg-green-50 rounded-xl border border-green-200 shadow-sm">
                    <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <form method="POST" action="{{ route('teacher.attendance.mark.store', $section->id) }}">
                    @csrf
                    <input type="hidden" name="attendance_date" value="{{ $today->format('Y-m-d') }}">

                    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wide">
                                {{ $students->count() }} Students
                            </span>
                        </div>
                        <button type="button" onclick="markAllPresent()" class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mark All Present
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-bold tracking-wider text-left">
                                    <th class="px-6 py-4 sticky left-0 bg-gray-50 z-20 w-16 border-r border-gray-200">No.</th>
                                    <th class="px-6 py-4 sticky left-16 bg-gray-50 z-20 w-64 border-r border-gray-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">Student Name</th>

                                    <th class="px-4 py-4 text-center w-24 border-r border-gray-200">
                                        <span class="block text-xs leading-3">Monthly</span>
                                        <span class="block text-[10px] text-gray-400">Absent</span>
                                    </th>

                                    <th class="px-4 py-4 text-center w-48 border-r border-gray-200">History (Last 5 Days)</th>

                                    <th class="px-6 py-4 text-center">Today's Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($students as $student)
                                    @php
                                        $checkedStatus = $attendanceData->get($student->id, 'absent');
                                        $absentCount = $monthlyAbsentCounts->get($student->id, 0);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors group">

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 sticky left-0 bg-white group-hover:bg-gray-50 z-10 border-r border-gray-100">
                                            {{ $student->admission_no }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white group-hover:bg-gray-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] border-r border-gray-100">
                                            <span class="text-sm font-bold text-gray-900">{{ $student->name }}</span>
                                        </td>

                                        <td class="px-4 py-4 text-center border-r border-gray-100">
                                            @if($absentCount > 0)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700 font-bold text-sm">
                                                    {{ $absentCount }}
                                                </span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 border-r border-gray-100">
                                            <div class="flex justify-center items-center space-x-2">
                                                @foreach ($lastFiveSchoolDays as $date)
                                                    @php
                                                        $dateString = $date->format('Y-m-d');
                                                        $status = $pastAttendance[$student->id][$dateString] ?? null;
                                                        $dayName = $date->format('D'); // Mon, Tue...
                                                    @endphp

                                                    <div class="flex flex-col items-center group/tooltip relative">
                                                        @if ($status == 'present')
                                                            <div class="w-6 h-6 rounded-md bg-green-100 text-green-700 flex items-center justify-center text-[10px] font-bold border border-green-200 cursor-default">P</div>
                                                        @elseif ($status == 'absent')
                                                            <div class="w-6 h-6 rounded-md bg-red-100 text-red-700 flex items-center justify-center text-[10px] font-bold border border-red-200 cursor-default">A</div>
                                                        @elseif ($status == 'late')
                                                            <div class="w-6 h-6 rounded-md bg-yellow-100 text-yellow-700 flex items-center justify-center text-[10px] font-bold border border-yellow-200 cursor-default">L</div>
                                                        @else
                                                            <div class="w-6 h-6 rounded-md bg-gray-100 text-gray-400 flex items-center justify-center text-[10px] border border-gray-200 cursor-default">-</div>
                                                        @endif

                                                        <div class="absolute bottom-full mb-1 hidden group-hover/tooltip:block bg-gray-800 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap z-50">
                                                            {{ $date->format('M d') }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        <td class="px-6 py-3">
                                            <div class="flex justify-center">
                                                <div class="inline-flex bg-gray-100 p-1 rounded-lg shadow-inner">

                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="peer sr-only" {{ $checkedStatus == 'present' ? 'checked' : '' }}>
                                                        <div class="px-5 py-2 rounded-md text-sm font-bold text-gray-500 transition-all peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm hover:text-gray-700">
                                                            P
                                                        </div>
                                                    </label>

                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="peer sr-only" {{ $checkedStatus == 'late' ? 'checked' : '' }}>
                                                        <div class="px-5 py-2 rounded-md text-sm font-bold text-gray-500 transition-all peer-checked:bg-white peer-checked:text-yellow-600 peer-checked:shadow-sm hover:text-gray-700">
                                                            L
                                                        </div>
                                                    </label>

                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="peer sr-only" {{ $checkedStatus == 'absent' ? 'checked' : '' }}>
                                                        <div class="px-5 py-2 rounded-md text-sm font-bold text-gray-500 transition-all peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm hover:text-gray-700">
                                                            A
                                                        </div>
                                                    </label>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No students found in this section.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end sticky bottom-0 z-30">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition shadow-lg transform hover:-translate-y-0.5">
                            Save Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function markAllPresent() {
            // Find all radio buttons with value 'present' and check them
            const presentRadios = document.querySelectorAll('input[type="radio"][value="present"]');
            presentRadios.forEach(radio => {
                radio.checked = true;
            });
        }
    </script>

</x-app-layout>
