<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Section') }}
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

                    <form method="POST" action="{{ route('admin.sections.update', $section->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="grade_id" class="block font-medium text-sm text-gray-700">{{ __('Grade') }}</label>
                            <select name="grade_id" id="grade_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">{{ __('Select a Grade') }}</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ $section->grade_id == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Section Name') }}</label>
                            <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                   type="text"
                                   name="name"
                                   value="{{ old('name', $section->name) }}"
                                   required
                                   autofocus />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
