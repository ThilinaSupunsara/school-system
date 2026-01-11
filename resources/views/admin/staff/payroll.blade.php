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
                    {{ __('Payroll Management') }}
                </h2>
                <p class="text-sm text-gray-500">Managing components for <span class="font-bold text-gray-800">{{ $staff->user->name }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="flex items-center p-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="flex flex-col gap-6">
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-100 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-green-50/50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <span class="bg-green-100 text-green-600 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </span>
                                Allowances (Earnings)
                            </h3>
                        </div>

                        <div class="p-6">
                            <form method="POST" action="{{ route('finance.staff.allowances.store', $staff->id) }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="allowance_name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Name</label>
                                        <input id="allowance_name" type="text" name="allowance_name" value="{{ old('allowance_name') }}" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 transition-colors text-sm py-2.5" placeholder="e.g. Transport">
                                    </div>
                                    <div>
                                        <label for="allowance_amount" class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount (LKR)</label>
                                        <input id="allowance_amount" type="number" step="0.01" min="0" name="allowance_amount" value="{{ old('allowance_amount') }}" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 transition-colors text-sm py-2.5" placeholder="0.00">
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-green-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                        Add Allowance
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Current Allowances</h4>
                        </div>
                        <ul class="divide-y divide-gray-50">
                            @forelse ($staff->allowances as $allowance)
                                <li class="px-6 py-4 flex justify-between items-center group hover:bg-green-50/30 transition-colors">
                                    <div>
                                        <span class="block text-sm font-bold text-gray-800">{{ $allowance->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-mono text-sm font-bold text-green-600">+ {{ number_format($allowance->amount, 2) }}</span>

                                        <form method="POST" action="{{ route('finance.allowances.destroy', $allowance->id) }}" onsubmit="return confirm('Remove this allowance?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Remove">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-10 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 mb-2 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm">No allowances added yet.</span>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-100 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-red-50/50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <span class="bg-red-100 text-red-600 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </span>
                                Deductions (Expenses)
                            </h3>
                        </div>

                        <div class="p-6">
                            <form method="POST" action="{{ route('finance.staff.deductions.store', $staff->id) }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="deduction_name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Name</label>
                                        <input id="deduction_name" type="text" name="deduction_name" value="{{ old('deduction_name') }}" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-red-500 transition-colors text-sm py-2.5" placeholder="e.g. EPF/ETF">
                                    </div>
                                    <div>
                                        <label for="deduction_amount" class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount (LKR)</label>
                                        <input id="deduction_amount" type="number" step="0.01" min="0" name="deduction_amount" value="{{ old('deduction_amount') }}" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-red-500 transition-colors text-sm py-2.5" placeholder="0.00">
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                        Add Deduction
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Current Deductions</h4>
                        </div>
                        <ul class="divide-y divide-gray-50">
                            @forelse ($staff->deductions as $deduction)
                                <li class="px-6 py-4 flex justify-between items-center group hover:bg-red-50/30 transition-colors">
                                    <div>
                                        <span class="block text-sm font-bold text-gray-800">{{ $deduction->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-mono text-sm font-bold text-red-600">- {{ number_format($deduction->amount, 2) }}</span>

                                        <form method="POST" action="{{ route('finance.deductions.destroy', $deduction->id) }}" onsubmit="return confirm('Remove this deduction?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Remove">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-10 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 mb-2 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd"></path><path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"></path></svg>
                                        <span class="text-sm">No deductions added yet.</span>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
