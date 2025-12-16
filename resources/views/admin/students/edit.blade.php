<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            {{ __('Edit Student Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('finance.students.update', $student->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="admission_no" class="block font-medium text-sm text-gray-700">{{ __('Admission Number') }}</label>
                                <input id="admission_no" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="text" name="admission_no"
                                       value="{{ old('admission_no', $student->admission_no) }}" required autofocus />
                            </div>

                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Full Name') }}</label>
                                <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="text" name="name"
                                       value="{{ old('name', $student->name) }}" required />
                            </div>

                            <div>
                                <label for="dob" class="block font-medium text-sm text-gray-700">{{ __('Date of Birth') }}</label>
                                <input id="dob" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="date" name="dob"
                                       value="{{ old('dob', $student->dob) }}" required />
                            </div>

                            <div>
                                <label for="section_id" class="block font-medium text-sm text-gray-700">{{ __('Class') }}</label>
                                <select name="section_id" id="section_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">{{ __('Select a Class') }}</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="parent_name" class="block font-medium text-sm text-gray-700">{{ __("Parent's Name") }}</label>
                                <input id="parent_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="text" name="parent_name"
                                       value="{{ old('parent_name', $student->parent_name) }}" required />
                            </div>

                            <div>
                                <label for="parent_phone" class="block font-medium text-sm text-gray-700">{{ __("Parent's Phone") }}</label>
                                <input id="parent_phone" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                       type="text" name="parent_phone"
                                       value="{{ old('parent_phone', $student->parent_phone) }}" required />
                            </div>

                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('finance.students.scholarships.assign', $student->id) }}"
                            class="text-green-600 hover:text-green-900">
                                Scholarships
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                                {{ __('Update Student') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
