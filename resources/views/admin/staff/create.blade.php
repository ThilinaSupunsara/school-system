<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Staff Member') }}
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

                    <form method="POST" action="{{ route('admin.staff.store') }}">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Login Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Full Name') }}</label>
                                <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="name" value="{{ old('name') }}" required autofocus />
                            </div>

                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                                <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="email" name="email" value="{{ old('email') }}" required />
                            </div>

                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
                                <input id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password" required />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirm Password') }}</label>
                                <input id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password_confirmation" required />
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Staff Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">{{ __('Role') }}</label>
                                <select name="role" id="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">{{ __('Select a Role') }}</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="teacher">Teacher</option>
                                </select>
                            </div>

                            <div>
                                <label for="designation" class="block font-medium text-sm text-gray-700">{{ __('Designation') }} (e.g. Maths Teacher)</label>
                                <input id="designation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="designation" value="{{ old('designation') }}" required />
                            </div>

                            <div>
                                <label for="join_date" class="block font-medium text-sm text-gray-700">{{ __('Joining Date') }}</label>
                                <input id="join_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="date" name="join_date" value="{{ old('join_date') }}" required />
                            </div>

                            <div>
                                <label for="phone" class="block font-medium text-sm text-gray-700">{{ __('Phone') }}</label>
                                <input id="phone" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="phone" value="{{ old('phone') }}" />
                            </div>

                            <div>
                                <label for="basic_salary" class="block font-medium text-sm text-gray-700">{{ __('Basic Salary') }} (LKR)</label>
                                <input id="basic_salary" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="number" step="0.01" name="basic_salary" value="{{ old('basic_salary') }}" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Register Staff') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
