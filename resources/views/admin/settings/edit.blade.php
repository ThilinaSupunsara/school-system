<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">School Name</label>
                                    <input type="text" name="school_name" value="{{ old('school_name', $settings->school_name) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Address</label>
                                    <textarea name="school_address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('school_address', $settings->school_address) }}</textarea>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $settings->email) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                </div>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">School Logo</label>

                                <div class="mb-4 p-4 border border-gray-200 rounded text-center">
                                    @if($settings->logo_path)
                                        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="School Logo" class="mx-auto h-32 object-contain">
                                    @else
                                        <span class="text-gray-400">No Logo Uploaded</span>
                                    @endif
                                </div>

                                <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 text-white">
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
