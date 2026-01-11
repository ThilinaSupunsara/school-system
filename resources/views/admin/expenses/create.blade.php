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
                    {{ __('Issue Petty Cash') }}
                </h2>
                <p class="text-sm text-gray-500">Record a new cash disbursement for school expenses.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-100">

                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </span>
                        Expense Details
                    </h3>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('finance.expenses.store') }}" x-data="{ type: '{{ old('recipient_type', 'staff') }}' }">
                        @csrf

                        <div class="space-y-6">

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <div class="relative w-full">
                                        <select name="category_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none pl-4 pr-10" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    <a href="{{ route('finance.expense-categories.index') }}" class="flex-shrink-0 px-4 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 flex items-center justify-center transition" title="Add New Category">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Description / Task <span class="text-red-500">*</span></label>
                                <input type="text" name="description" value="{{ old('description') }}" required
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3"
                                       placeholder="e.g. Purchase of paint for classroom A">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Amount to Issue (LKR) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="amount_given" value="{{ old('amount_given') }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-mono text-sm py-3 pl-3"
                                           placeholder="0.00">
                                </div>
                            </div>

                            <div class="bg-gray-50 p-1 rounded-xl flex">
                                <button type="button" @click="type = 'staff'"
                                        :class="{'bg-white text-blue-600 shadow-sm': type === 'staff', 'text-gray-500 hover:text-gray-700': type !== 'staff'}"
                                        class="flex-1 py-2 rounded-lg text-sm font-bold transition-all duration-200">
                                    Staff Member
                                </button>
                                <button type="button" @click="type = 'external'"
                                        :class="{'bg-white text-blue-600 shadow-sm': type === 'external', 'text-gray-500 hover:text-gray-700': type !== 'external'}"
                                        class="flex-1 py-2 rounded-lg text-sm font-bold transition-all duration-200">
                                    External Person
                                </button>
                                <input type="hidden" name="recipient_type" x-model="type">
                            </div>

                            <div x-show="type === 'staff'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Select Staff Member <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="staff_id" :required="type === 'staff'"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3 appearance-none pl-4 pr-10">
                                        <option value="">-- Choose Staff --</option>
                                        @foreach($staffMembers as $staff)
                                            <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->user->name }} ({{ $staff->designation }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div x-show="type === 'external'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                                <label class="block text-sm font-bold text-gray-700 mb-2">External Person Name <span class="text-red-500">*</span></label>
                                <input type="text" name="external_name" value="{{ old('external_name') }}" :required="type === 'external'"
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-3"
                                       placeholder="e.g. Driver Kamal">
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Issue Cash
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
