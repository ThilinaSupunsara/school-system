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
                    {{ __('Settle Expense') }}
                </h2>
                <p class="text-sm text-gray-500">Reconcile cash advance for {{ $expense->description }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl shadow-gray-100 sm:rounded-2xl border border-gray-100">

                <div class="bg-gray-50/50 p-6 border-b border-gray-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Issued To</p>
                            <p class="font-bold text-gray-900">{{ $expense->recipient_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Amount Given</p>
                            <p class="font-mono font-bold text-lg text-blue-600">LKR {{ number_format($expense->amount_given, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('finance.expenses.update', $expense->id) }}" enctype="multipart/form-data"
                          x-data="{ given: {{ $expense->amount_given }}, spent: {{ $expense->amount_given }} }">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Actual Amount Spent (LKR)</label>
                                <input type="number" step="0.01" name="amount_spent" x-model="spent" required
                                       class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-mono text-lg py-3 pl-4 transition-colors">
                            </div>

                            <div class="p-4 rounded-xl border transition-colors duration-300"
                                 :class="{
                                     'bg-green-50 border-green-100 text-green-800': (given - spent) > 0,
                                     'bg-red-50 border-red-100 text-red-800': (given - spent) < 0,
                                     'bg-gray-50 border-gray-200 text-gray-600': (given - spent) == 0
                                 }">

                                <div x-show="(given - spent) > 0" class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        <span class="font-bold">Collect Refund:</span>
                                    </div>
                                    <span class="font-mono font-bold text-xl">LKR <span x-text="(given - spent).toFixed(2)"></span></span>
                                </div>

                                <div x-show="(given - spent) < 0" class="flex items-center justify-between" style="display: none;">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        <span class="font-bold">Reimburse (Pay):</span>
                                    </div>
                                    <span class="font-mono font-bold text-xl">LKR <span x-text="(spent - given).toFixed(2)"></span></span>
                                </div>

                                <div x-show="(given - spent) == 0" class="flex items-center justify-center text-sm font-medium" style="display: none;">
                                    Accounts Balanced (No refund needed)
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Upload Receipt (Optional)</label>
                                <div class="flex items-center justify-center w-full">
                                    <label class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-7">
                                            <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">Select receipt image</p>
                                        </div>
                                        <input type="file" name="receipt" class="opacity-0" />
                                    </label>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-100">
                                <button type="submit" class="w-full flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-green-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Finalize & Settle
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
