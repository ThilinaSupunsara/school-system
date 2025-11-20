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
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                            <div class="md:col-span-2">
                                <label for="section_id" class="block font-medium text-sm text-gray-700">{{ __('Select Class (Section)') }}</label>
                                <select name="section_id" id="section_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">-- Choose a Class --</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="month" class="block font-medium text-sm text-gray-700">{{ __('Month') }}</label>
                                <select name="month" id="month" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex items-end gap-4">
                                <div class="w-full">
                                    <label for="year" class="block font-medium text-sm text-gray-700">{{ __('Year') }}</label>
                                    <input id="year" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                           type="number" name="year" value="{{ request('year', now()->year) }}" required />
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 h-10">
                                    {{ __('View') }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

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
