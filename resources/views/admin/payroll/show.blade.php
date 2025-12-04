<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('finance.payroll.index') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md
                    font-semibold text-xs text-gray-700 uppercase tracking-widest
                    hover:text-gray-900 focus:outline-none focus:ring-2
                    focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>

            </a>
            Manage Payroll: {{ $payroll->staff->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Payslip Details</h3>

                        <a href="{{ route('finance.payroll.print', $payroll->id) }}" target="_blank" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 text-sm">
                            Print Payslip
                        </a>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Employee</p>
                            <p class="font-bold">{{ $payroll->staff->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Month/Year</p>
                            <p class="font-bold">{{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</p>
                        </div>
                    </div>

                    <table class="w-full text-sm border-t">
                        <tr>
                            <td class="py-2">Basic Salary</td>
                            <td class="text-right font-bold">{{ number_format($payroll->basic_salary, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Total Allowances (+)</td>
                            <td class="text-right text-green-600">{{ number_format($payroll->total_allowances, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Total Deductions (-)</td>
                            <td class="text-right text-red-600">{{ number_format($payroll->total_deductions, 2) }}</td>
                        </tr>
                        <tr class="border-t border-b bg-gray-50">
                            <td class="py-2 font-bold">NET SALARY</td>
                            <td class="text-right font-bold text-lg">{{ number_format($payroll->net_salary, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-bold text-green-700">Total Paid</td>
                            <td class="text-right font-bold text-green-700">{{ number_format($payroll->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-bold text-red-700">Balance Due</td>
                            <td class="text-right font-bold text-red-700 text-lg">{{ number_format($balance, 2) }}</td>
                        </tr>
                    </table>

                    <h4 class="mt-8 mb-2 font-bold text-gray-700">Payment History</h4>
                    <table class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Date</th>
                                <th class="p-2 text-left">Method</th>
                                <th class="p-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payroll->payments as $payment)
                                <tr class="border-t">
                                    <td class="p-2">{{ $payment->payment_date }}</td>
                                    <td class="p-2 capitalize">{{ $payment->method }}</td>
                                    <td class="p-2 text-right">{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-2 text-center text-gray-500">No payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:col-span-1 bg-white shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Record Payment</h3>

                    @if($balance > 0)
                        <form method="POST" action="{{ route('finance.payroll.payments.store', $payroll->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Amount (LKR)</label>
                                <input type="number" step="0.01" max="{{ $balance }}" name="amount" value="{{ $balance }}" class="w-full rounded border-gray-300" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded border-gray-300" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Method</label>
                                <select name="method" class="w-full rounded border-gray-300">
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-blue-800 text-white py-2 rounded hover:bg-blue-900 font-bold">
                                Save Payment
                            </button>
                        </form>
                    @else
                        <div class="p-4 bg-green-100 text-green-700 rounded text-center">
                            <span class="font-bold text-lg">Fully Paid</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
