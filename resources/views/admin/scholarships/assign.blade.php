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
                    {{ __('Scholarship Assignment') }}
                </h2>
                <p class="text-sm text-gray-500">Manage financial aid for specific students.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row items-center gap-6">
                <div class="h-20 w-20 flex-shrink-0">
                    <img class="h-full w-full rounded-full object-cover border-4 border-gray-50"
                         src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&color=7F9CF5&background=EBF4FF&size=128"
                         alt="{{ $student->name }}">
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $student->name }}</h1>
                    <div class="flex flex-col md:flex-row gap-2 md:gap-6 text-sm text-gray-500 mt-1 justify-center md:justify-start">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            {{ $student->admission_no }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $student->section->grade->name }} - {{ $student->section->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 h-fit">
                    <div class="mb-6 pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </span>
                            Assign Scholarship
                        </h3>
                    </div>

                    <form method="POST" action="{{ route('finance.students.scholarships.store', $student->id) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Select Scholarship Type</label>
                                <div class="relative">
                                    <select name="scholarship_id" required class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors py-3 pl-4 pr-10 appearance-none">
                                        <option value="">-- Choose Type --</option>
                                        @foreach ($scholarships as $scholarship)
                                            <option value="{{ $scholarship->id }}">
                                                {{ $scholarship->name }} (LKR {{ number_format($scholarship->amount, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200 transform hover:-translate-y-0.5">
                                Assign to Student
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-fit">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Financial Aid</h3>
                        <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-1 rounded-full">{{ $student->scholarships->count() }} Active</span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($student->scholarships as $scholarship)
                            <div class="p-6 flex items-center justify-between group hover:bg-gray-50 transition-colors">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $scholarship->name }}</h4>
                                    <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                        LKR {{ number_format($scholarship->amount, 2) }} Discount
                                    </span>
                                </div>

                                <form method="POST" action="{{ route('finance.students.scholarships.destroy', [$student->id, $scholarship->id]) }}"
                                      onsubmit="return confirm('Are you sure you want to remove this scholarship from the student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove Scholarship">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">No active scholarships</p>
                                <p class="text-xs text-gray-500 mt-1">Assign a scholarship using the form.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
