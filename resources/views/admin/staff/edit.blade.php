<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           <button onclick="window.history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            {{ __('Edit Staff Member') }}
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

                    <form method="POST" action="{{ route('finance.staff.update', $staff->id) }}">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Login Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Full Name') }}</label>
                                <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="name" value="{{ old('name', $staff->user->name) }}" required autofocus />
                            </div>

                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                                <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="email" name="email" value="{{ old('email', $staff->user->email) }}" required />
                            </div>

                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('New Password') }}</label>
                                <input id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password" placeholder="Leave blank to keep current password" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirm New Password') }}</label>
                                <input id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password_confirmation" />
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Staff Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">{{ __('Role') }}</label>
                                <select name="role" id="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">{{ __('Select a Role') }}</option>
                                    <option value="accountant" {{ $staff->user->role == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                    <option value="teacher" {{ $staff->user->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                </select>
                            </div>

                            <div>
                                <label for="designation" class="block font-medium text-sm text-gray-700">{{ __('Designation') }}</I></label>
                                <input id="designation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="designation" value="{{ old('designation', $staff->designation) }}" required />
                            </div>

                            <div>
                                <label for="join_date" class="block font-medium text-sm text-gray-700">{{ __('Joining Date') }}</Label>
                                <input id="join_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="date" name="join_date" value="{{ old('join_date', $staff->join_date) }}" required />
                            </div>

                            <div>
                                <label for="phone" class="block font-medium text-sm text-gray-700">{{ __('Phone') }}</Label>
                                <input id="phone" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="phone" value="{{ old('phone', $staff->phone) }}" />
                            </div>

                            <div>
                                <label for="basic_salary" class="block font-medium text-sm text-gray-700">{{ __('Basic Salary') }} (LKR)</label>
                                <input id="basic_salary" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="number" step="0.01" name="basic_salary" value="{{ old('basic_salary', $staff->basic_salary) }}" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                                {{ __('Update Staff') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
