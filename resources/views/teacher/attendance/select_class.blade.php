<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Class for Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">Your Assigned Classes</h3>

                    @if ($assignedSections->isEmpty())
                        <p class="text-gray-500">No classes have been assigned to you as a Class Teacher.</p>
                        <p class="text-gray-500">Please contact the administration.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($assignedSections as $section)
                                <a href="{{ route('teacher.attendance.mark.form', $section->id) }}" class="block p-6 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
                                    <p class="font-bold text-2xl">{{ $section->grade->name }} - {{ $section->name }}</p>
                                    <p>{{ $section->students->count() }} Students</p>
                                    <span class="inline-block mt-4 px-3 py-1 bg-white text-blue-700 text-sm font-semibold rounded-full">
                                        Mark Today's Attendance
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
