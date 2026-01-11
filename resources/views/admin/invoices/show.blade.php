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
                    {{ __('Invoice Details') }} <span class="text-gray-400">#{{ $invoice->id }}</span>
                </h2>
                <p class="text-sm text-gray-500">View invoice details and record payments.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-start gap-3">
                    <svg class="h-5 w-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h3 class="text-sm font-bold text-green-800">Success</h3>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Error</h3>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl shadow-gray-200/50 rounded-2xl overflow-hidden border border-gray-100">

                        <div class="p-8 border-b border-gray-100 bg-gray-50/30">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start gap-4">
                                    @if(isset($schoolSettings) && $schoolSettings->logo_path)
                                        <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}" alt="Logo" class="h-16 w-16 object-contain rounded-lg bg-white p-1 border border-gray-100">
                                    @endif
                                    <div>
                                        <h1 class="text-xl font-bold uppercase text-gray-900 tracking-wide">
                                            {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
                                        </h1>
                                        <div class="text-sm text-gray-500 mt-1 space-y-0.5">
                                            <p>{{ $schoolSettings->school_address ?? 'Address Line 1' }}</p>
                                            <p>{{ $schoolSettings->phone ?? 'Phone Number' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('finance.invoices.print', $invoice->id) }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print / PDF
                                    </a>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-between items-end">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Billed To</p>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $invoice->student->name }}</h3>
                                    <p class="text-sm text-gray-600">ID: <span class="font-mono">{{ $invoice->student->admission_no }}</span></p>
                                    <p class="text-sm text-gray-600">{{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Invoice Info</p>
                                    <p class="text-sm font-medium text-gray-900">Date: <span class="font-mono">{{ $invoice->invoice_date }}</span></p>
                                    <p class="text-sm font-medium {{ $invoice->due_date < now() && $invoice->status != 'paid' ? 'text-red-600' : 'text-gray-900' }}">
                                        Due: <span class="font-mono">{{ $invoice->due_date }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-8 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-8 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($invoice->invoiceItems as $item)
                                    <tr>
                                        <td class="px-8 py-4 text-sm text-gray-900 font-medium">{{ $item->feeCategory->name }}</td>
                                        <td class="px-8 py-4 text-sm text-gray-900 text-right font-mono">{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50/50">
                                    <tr>
                                        <td class="px-8 py-4 text-right text-sm font-medium text-gray-500">Total Amount</td>
                                        <td class="px-8 py-4 text-right text-sm font-bold text-gray-900 font-mono">{{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-8 py-2 text-right text-sm font-medium text-green-600">Paid to Date</td>
                                        <td class="px-8 py-2 text-right text-sm font-bold text-green-600 font-mono">- {{ number_format($invoice->paid_amount, 2) }}</td>
                                    </tr>
                                    <tr class="bg-blue-50/50">
                                        <td class="px-8 py-4 text-right text-base font-bold text-blue-800">Balance Due</td>
                                        <td class="px-8 py-4 text-right text-xl font-extrabold text-blue-800 font-mono">LKR {{ number_format($balance, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="p-8 border-t border-gray-100">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Payment History</h4>
                            @if($invoice->payments->count() > 0)
                                <div class="overflow-hidden rounded-lg border border-gray-100">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            @foreach($invoice->payments as $payment)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $payment->payment_date }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600 capitalize">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ str_replace('_', ' ', $payment->method) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-sm font-medium text-gray-900 text-right font-mono">{{ number_format($payment->amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">No payments recorded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Current Status</h3>

                        @if($balance <= 0)
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Fully Paid</h2>
                            <p class="text-sm text-gray-500 mt-1">No further action needed.</p>
                        @elseif($invoice->paid_amount > 0)
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Partially Paid</h2>
                            <p class="text-sm text-gray-500 mt-1">Balance: LKR {{ number_format($balance, 2) }}</p>
                        @else
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Payment Pending</h2>
                            <p class="text-sm text-gray-500 mt-1">Due Date: {{ $invoice->due_date }}</p>
                        @endif
                    </div>

                    @if($balance > 0)
                        <div class="bg-white rounded-2xl shadow-lg shadow-blue-100 border border-blue-100 p-6">

                            @if($invoice->total_amount == 0 && $invoice->status != 'paid')
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Mark as Settled</h3>
                                <p class="text-sm text-gray-500 mb-4">This invoice has a total of 0.00 (Scholarship). Mark it as settled to close it.</p>
                                <form method="POST" action="{{ route('finance.invoices.settle', $invoice->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition shadow-md">
                                        Mark as Settled
                                    </button>
                                </form>

                            @else
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Record Payment
                                </h3>

                                <form method="POST" action="{{ route('finance.invoices.storePayment', $invoice->id) }}">
                                    @csrf
                                    <div class="space-y-4">

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount (LKR)</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0.01" max="{{ $balance }}" name="amount" value="{{ old('amount', $balance) }}" required
                                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-mono text-sm py-2.5 pl-3">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date</label>
                                            <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required
                                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Method</label>
                                            <select name="method" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                                <option value="cash">Cash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="online">Online Payment</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
                                            Process Payment
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
