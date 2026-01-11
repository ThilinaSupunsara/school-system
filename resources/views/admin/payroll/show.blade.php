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
                    {{ __('Payroll Details') }}
                </h2>
                <p class="text-sm text-gray-500">Processing salary for <span class="font-bold text-gray-800">{{ $payroll->staff->user->name }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white shadow-xl shadow-gray-200/50 rounded-2xl overflow-hidden border border-gray-100">

                        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide">Payslip Summary</h3>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</p>
                            </div>

                            <a href="{{ route('finance.payroll.print', $payroll->id) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Payslip
                            </a>
                        </div>

                        <div class="p-8">
                            <table class="w-full">
                                <tbody>
                                    <tr class="border-b border-dashed border-gray-200">
                                        <td class="py-4 text-sm font-medium text-gray-600">Basic Salary</td>
                                        <td class="py-4 text-right text-sm font-bold text-gray-900">LKR {{ number_format($payroll->basic_salary, 2) }}</td>
                                    </tr>

                                    <tr class="border-b border-dashed border-gray-200">
                                        <td class="py-4 flex items-center text-sm font-medium text-gray-600">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Total Allowances
                                        </td>
                                        <td class="py-4 text-right text-sm font-bold text-green-600">+ {{ number_format($payroll->total_allowances, 2) }}</td>
                                    </tr>

                                    <tr class="border-b border-gray-200">
                                        <td class="py-4 flex items-center text-sm font-medium text-gray-600">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                            Total Deductions
                                        </td>
                                        <td class="py-4 text-right text-sm font-bold text-red-600">- {{ number_format($payroll->total_deductions, 2) }}</td>
                                    </tr>

                                    <tr class="bg-blue-50/50">
                                        <td class="py-6 pl-4 text-base font-bold text-blue-900 uppercase">Net Salary</td>
                                        <td class="py-6 pr-4 text-right text-2xl font-extrabold text-blue-700">LKR {{ number_format($payroll->net_salary, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-6 flex justify-between items-center text-sm">
                                <span class="text-gray-500">Amount Paid So Far:</span>
                                <span class="font-bold text-green-700">LKR {{ number_format($payroll->paid_amount, 2) }}</span>
                            </div>
                            <div class="mt-2 flex justify-between items-center text-base border-t border-gray-100 pt-2">
                                <span class="font-bold text-gray-700">Balance Due:</span>
                                <span class="font-bold text-red-600">LKR {{ number_format($balance, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Payment History</h4>
                        </div>
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500">Date</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500">Method</th>
                                    <th class="px-6 py-3 text-right font-medium text-gray-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($payroll->payments as $payment)
                                    <tr>
                                        <td class="px-6 py-3 text-gray-600">{{ $payment->payment_date }}</td>
                                        <td class="px-6 py-3 text-gray-600 capitalize">
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ $payment->method }}</span>
                                        </td>
                                        <td class="px-6 py-3 text-right font-mono font-medium text-gray-900">{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-6 text-center text-gray-400 italic">No payments recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="lg:col-span-1">

                    <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                            Record Payment
                        </h3>

                        @if($balance > 0)
                            <form method="POST" action="{{ route('finance.payroll.payments.store', $payroll->id) }}">
                                @csrf
                                <div class="space-y-5">

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount (LKR)</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" max="{{ $balance }}" name="amount" value="{{ $balance }}" required
                                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-mono text-sm py-3 pl-3">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date</label>
                                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-3">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Method</label>
                                        <select name="method" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-3">
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
                                        Confirm Payment
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">Salary Fully Paid</h4>
                                <p class="text-sm text-gray-500 mt-1">All dues for this period have been settled.</p>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
