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
                    {{ __('Edit Student Details') }}
                </h2>
                <p class="text-sm text-gray-500">Update information for {{ $student->name }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Update Failed</h3>
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

            <form method="POST" action="{{ route('finance.students.update', $student->id) }}">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-100">

                    <div class="p-8 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            Student Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            <div class="col-span-1">
                                <label for="admission_no" class="block text-sm font-bold text-gray-700 mb-2">Admission Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <input id="admission_no" type="text" name="admission_no" value="{{ old('admission_no', $student->admission_no) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input id="name" type="text" name="name" value="{{ old('name', $student->name) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label for="dob" class="block text-sm font-bold text-gray-700 mb-2">Date of Birth</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input id="dob" type="date" name="dob" value="{{ old('dob', $student->dob) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label for="section_id" class="block text-sm font-bold text-gray-700 mb-2">Assign Class</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <select name="section_id" id="section_id" required
                                            class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none">
                                        <option value="">Select a Class</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                                {{ $section->grade->name }} - {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="p-8 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                            Guardian Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            <div class="col-span-1">
                                <label for="parent_name" class="block text-sm font-bold text-gray-700 mb-2">Parent/Guardian Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input id="parent_name" type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label for="parent_phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <input id="parent_phone" type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 bg-white focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors sm:text-sm py-3">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="px-8 py-6 bg-gray-50 flex items-center justify-between border-t border-gray-100">

                        <div>
                            @can('Scholarships.view')
                            <a href="{{ route('finance.students.scholarships.assign', $student->id) }}"
                               class="inline-flex items-center text-sm font-bold text-green-700 hover:text-green-800 bg-green-100 hover:bg-green-200 px-4 py-2 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Manage Scholarships
                            </a>
                            @endcan
                        </div>

                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Update Student
                        </button>

                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>
