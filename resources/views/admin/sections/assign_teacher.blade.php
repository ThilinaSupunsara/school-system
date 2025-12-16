<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('finance.sections.index') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md
                    font-semibold text-xs text-gray-700 uppercase tracking-widest
                    hover:text-gray-900 focus:outline-none focus:ring-2
                    focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>

            </a>
            Assign Class Teacher for: {{ $section->grade->name }} - {{ $section->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Current Class Teacher</h3>
                        @if ($section->classTeacher)
                            <p class="mt-1 text-xl font-semibold text-blue-600">
                                {{ $section->classTeacher->user->name }}
                            </p>
                            <form method="POST" action="{{ route('finance.sections.assign_teacher.remove', $section->id) }}" onsubmit="return confirm('Are you sure you want to remove this teacher?');" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                    Remove Teacher
                                </button>
                            </form>
                        @else
                            <p class="mt-1 text-gray-500">No class teacher assigned yet.</p>
                        @endif
                    </div>

                    <hr class="my-6">

                    <form method="POST" action="{{ route('finance.sections.assign_teacher.store', $section->id) }}">
                        @csrf
                        <h3 class="text-lg font-medium text-gray-900">Assign / Change Teacher</h3>

                        <div class="mt-4">
                            <label for="class_teacher_id" class="block font-medium text-sm text-gray-700">{{ __('Select Teacher') }}</label>
                            <select name="class_teacher_id" id="class_teacher_id" class="block mt-1 w-full md:w-1/2 rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Teacher') }}</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $section->class_teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }} ({{ $teacher->designation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-start mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                                {{ __('Save Assignment') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
