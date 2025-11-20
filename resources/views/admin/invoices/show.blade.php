<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Details') }}: #{{ $invoice->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="border-b pb-6 mb-6 flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    @if(isset($schoolSettings) && $schoolSettings->logo_path)
                                        <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}" alt="Logo" class="h-16 w-auto object-contain">
                                    @endif

                                    <div>
                                        <h1 class="text-2xl font-bold uppercase text-gray-800">
                                            {{ $schoolSettings->school_name ?? 'Your School Name' }}
                                        </h1>
                                        <p class="text-sm text-gray-500">
                                            {{ $schoolSettings->school_address ?? '' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $schoolSettings->phone ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Invoice to:</h3>
                                    <p class="font-bold text-xl">{{ $invoice->student->name }}</p>
                                    <p>{{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}</p>
                                    <p>Adm No: {{ $invoice->student->admission_no }}</p>
                                </div>
                                <div class="text-right">
                                    <h3 class="text-lg font-medium text-gray-900">Invoice #{{ $invoice->id }}</h3>
                                    <p>Date: {{ $invoice->invoice_date }}</p>
                                    <p>Due Date: {{ $invoice->due_date }}</p>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 mb-6">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->invoiceItems as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $item->feeCategory->name }}</td>
                                        <td class="px-6 py-4 text-right">{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td class="px-6 py-3 text-right font-bold uppercase">Total Amount:</td>
                                        <td class="px-6 py-3 text-right font-bold">{{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-3 text-right font-bold uppercase">Amount Paid:</td>
                                        <td class="px-6 py-3 text-right font-bold">{{ number_format($invoice->paid_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-3 text-right font-bold uppercase text-lg text-blue-600">Balance Due:</td>
                                        <td class="px-6 py-3 text-right font-bold text-lg text-blue-600">{{ number_format($balance, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4">{{ $payment->payment_date }}</td>
                                        <td class="px-6 py-4">{{ $payment->method == 'bank_transfer' ? 'Bank Transfer' : 'Cash' }}</td>
                                        <td class="px-6 py-4 text-right">{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No payments recorded yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Record Payment</h3>

                            @if($balance > 0)
                            <form method="POST" action="{{ route('finance.invoices.storePayment', $invoice->id) }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="amount" class="block font-medium text-sm text-gray-700">{{ __('Amount (LKR)') }}</label>
                                    <input id="amount" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                           type="number" step="0.01" min="0.01" max="{{ $balance }}"
                                           name="amount"
                                           value="{{ old('amount', $balance) }}"
                                           required />
                                </div>

                                <div class="mb-4">
                                    <label for="payment_date" class="block font-medium text-sm text-gray-700">{{ __('Payment Date') }}</label>
                                    <input id="payment_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                           type="date"
                                           name="payment_date"
                                           value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                           required />
                                </div>

                                <div class="mb-4">
                                    <label for="method" class="block font-medium text-sm text-gray-700">{{ __('Payment Method') }}</label>
                                    <select name="method" id="method" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                        <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 ...">
                                        {{ __('Record Payment') }}
                                    </button>
                                </div>
                            </form>
                            @else
                            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded text-center">
                                <p class="font-bold text-lg">Fully Paid</p>
                                <p>This invoice has been fully paid.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
