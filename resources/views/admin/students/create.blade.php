<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Student') }}
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

                    <form method="POST" action="{{ route('admin.students.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="admission_no" class="block font-medium text-sm text-gray-700">{{ __('Admission Number') }}</label>
                                <input id="admission_no" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="admission_no" value="{{ old('admission_no') }}" required autofocus />
                            </div>

                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Full Name') }}</label>
                                <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="name" value="{{ old('name') }}" required />
                            </div>

                            <div>
                                <label for="dob" class="block font-medium text-sm text-gray-700">{{ __('Date of Birth') }}</label>
                                <input id="dob" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="date" name="dob" value="{{ old('dob') }}" required />
                            </div>

                            <div>
                                <label for="section_id" class="block font-medium text-sm text-gray-700">{{ __('Class') }}</label>
                                <select name="section_id" id="section_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">{{ __('Select a Class') }}</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">
                                            {{ $section->grade->name }} - {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="parent_name" class="block font-medium text-sm text-gray-700">{{ __("Parent's Name") }}</label>
                                <input id="parent_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="parent_name" value="{{ old('parent_name') }}" required />
                            </div>

                            <div>
                                <label for="parent_phone" class="block font-medium text-sm text-gray-700">{{ __("Parent's Phone") }}</label>
                                <input id="parent_phone" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="parent_phone" value="{{ old('parent_phone') }}" required />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Register Student') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
