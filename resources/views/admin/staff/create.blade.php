<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="p-2 rounded-full bg-white text-gray-600 shadow-sm border border-gray-200 hover:bg-gray-50 hover:text-blue-600 transition-all duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('New Staff Registration') }}
                </h2>
                <p class="text-sm text-gray-500">Create a new staff account and assign roles.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Registration Failed</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('finance.staff.store') }}" x-data="{ showPassword: false }">
                @csrf

                <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-2">

                        <div class="p-8 border-b md:border-b-0 md:border-r border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                <span class="bg-purple-100 text-purple-600 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </span>
                                Login Credentials
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 transition-colors sm:text-sm py-3"
                                           placeholder="John Doe">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 transition-colors sm:text-sm py-3"
                                           placeholder="john@school.com">
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                                    <div class="relative">
                                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 transition-colors sm:text-sm py-3 pr-10">

                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.325m-3.641 4.192a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                                    <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>
                        </div>

                        <div class="p-8 bg-gray-50/30">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                Employment Details
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label for="role" class="block text-sm font-bold text-gray-700 mb-2">System Role</label>
                                    <select name="role" id="role" required
                                            class="w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none">
                                        <option value="">Select a Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="designation" class="block text-sm font-bold text-gray-700 mb-2">Designation</label>
                                    <input id="designation" type="text" name="designation" value="{{ old('designation') }}" required
                                           class="w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3"
                                           placeholder="e.g. Senior Teacher">
                                </div>

                                <div>
                                    <label for="join_date" class="block text-sm font-bold text-gray-700 mb-2">Joining Date</label>
                                    <input id="join_date" type="date" name="join_date" value="{{ old('join_date') }}" required
                                           class="w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                           class="w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3"
                                           placeholder="07X XXX XXXX">
                                </div>

                                <div>
                                    <label for="basic_salary" class="block text-sm font-bold text-gray-700 mb-2">Basic Salary (LKR)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                                        </div>
                                        <input id="basic_salary" type="number" step="0.01" name="basic_salary" value="{{ old('basic_salary') }}"
                                               class="w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 pl-12"
                                               placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="px-8 py-6 bg-gray-50 flex items-center justify-end border-t border-gray-100">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Create Account
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
