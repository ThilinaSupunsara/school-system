<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            Manage Scholarships for: {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold">{{ $student->name }}</h3>
                    <p class="text-gray-600">Adm No: {{ $student->admission_no }} | Class: {{ $student->section->grade->name }} - {{ $student->section->name }}</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-gray-600 hover:text-gray-900 underline">Back to Students</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Scholarship</h3>
                    <form method="POST" action="{{ route('admin.students.scholarships.store', $student->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Select Scholarship</label>
                            <select name="scholarship_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">-- Choose --</option>
                                @foreach ($scholarships as $scholarship)
                                    <option value="{{ $scholarship->id }}">
                                        {{ $scholarship->name }} (LKR {{ number_format($scholarship->amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">
                            Assign to Student
                        </button>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Active Scholarships</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($student->scholarships as $scholarship)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ $scholarship->name }}</p>
                                    <p class="text-sm text-green-600">LKR {{ number_format($scholarship->amount, 2) }} Off</p>
                                </div>

                                <form method="POST" action="{{ route('admin.students.scholarships.destroy', [$student->id, $scholarship->id]) }}" onsubmit="return confirm('Remove this scholarship?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-bold">Remove</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-500 italic">No scholarships assigned yet.</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
