<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip #{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
        }

        .payslip-container {
            background: white;
            width: 210mm;
            min-height: 148mm; /* A5 Landscape look or half A4 */
            margin: 2rem auto;
            padding: 15mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        @media print {
            body {
                background: white;
                margin: 0;
            }
            .payslip-container {
                width: 100%;
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print fixed top-0 left-0 w-full bg-white border-b border-gray-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div>
            <h2 class="font-bold text-gray-800">Payslip Preview</h2>
            <p class="text-xs text-gray-500">{{ $payroll->staff->user->name }} - {{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.close()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition">
                Close
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Payslip
            </button>
        </div>
    </div>

    <div class="h-20 no-print"></div>

    <div class="payslip-container rounded-sm">

        <div class="border-b border-gray-200 pb-6 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex gap-4">
                    @if(isset($schoolSettings) && $schoolSettings->logo_path)
                        <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}" alt="Logo" class="h-20 w-auto object-contain">
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 uppercase tracking-wide">
                            {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $schoolSettings->school_address ?? 'Address Line 1' }}</p>
                        <p class="text-sm text-gray-500">{{ $schoolSettings->phone ?? 'Phone Number' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-extrabold text-gray-200 uppercase tracking-widest">PAYSLIP</h2>
                    <p class="text-sm font-bold text-gray-700 mt-1">
                        {{ \Carbon\Carbon::create()->month($payroll->month)->format('F Y') }}
                    </p>
                    <p class="text-xs text-gray-500">#PAY-{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Employee Details</h3>
                <p class="text-lg font-bold text-gray-900">{{ $payroll->staff->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $payroll->staff->designation ?? 'Staff' }}</p>
                <p class="text-xs text-gray-500 mt-1">Joined: {{ $payroll->staff->join_date ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Payment Status</h3>
                @if($payroll->status == 'paid')
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded border border-green-200 text-xs font-bold uppercase">PAID</span>
                @else
                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded border border-blue-200 text-xs font-bold uppercase">GENERATED</span>
                @endif

                @if($payroll->status != 'paid')
                    <p class="text-sm text-red-600 font-bold mt-2">Due: LKR {{ number_format($payroll->net_salary - $payroll->paid_amount, 2) }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">

            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                    <h4 class="text-xs font-bold text-gray-600 uppercase">Earnings</h4>
                </div>
                <table class="w-full text-sm">
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-2 text-gray-700">Basic Salary</td>
                            <td class="px-4 py-2 text-right font-medium">{{ number_format($payroll->basic_salary, 2) }}</td>
                        </tr>
                        @foreach($payroll->staff->allowances as $allowance)
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-2 text-gray-600">{{ $allowance->name }}</td>
                            <td class="px-4 py-2 text-right text-gray-600">{{ number_format($allowance->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    <tfoot class="bg-green-50">
                        <tr>
                            <td class="px-4 py-2 font-bold text-green-800">Total Earnings</td>
                            <td class="px-4 py-2 text-right font-bold text-green-800">{{ number_format($payroll->basic_salary + $payroll->total_allowances, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-hidden h-fit">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                    <h4 class="text-xs font-bold text-gray-600 uppercase">Deductions</h4>
                </div>
                <table class="w-full text-sm">
                    <tbody>
                        @forelse($payroll->staff->deductions as $deduction)
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-2 text-gray-600">{{ $deduction->name }}</td>
                            <td class="px-4 py-2 text-right text-gray-600">{{ number_format($deduction->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="px-4 py-2 text-gray-400 italic" colspan="2">No deductions</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-red-50">
                        <tr>
                            <td class="px-4 py-2 font-bold text-red-800">Total Deductions</td>
                            <td class="px-4 py-2 text-right font-bold text-red-800">{{ number_format($payroll->total_deductions, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <div class="flex justify-end mb-12">
            <div class="w-1/2 bg-gray-900 text-white rounded-lg p-4 flex justify-between items-center shadow-md">
                <span class="text-sm font-bold uppercase tracking-wider">Net Salary</span>
                <span class="text-2xl font-bold">LKR {{ number_format($payroll->net_salary, 2) }}</span>
            </div>
        </div>

        <div class="flex justify-between items-end mt-auto pt-8 border-t border-gray-200">
            <div class="text-center">
                <div class="w-40 border-b border-gray-400 mb-2"></div>
                <p class="text-xs text-gray-500 font-medium uppercase">Employee Signature</p>
            </div>
            <div class="text-center">
                <div class="w-40 border-b border-gray-400 mb-2"></div>
                <p class="text-xs text-gray-500 font-medium uppercase">Authorized Signature</p>
            </div>
        </div>

        <div class="text-center mt-8 text-[10px] text-gray-400">
            Generated by System on {{ now()->format('Y-m-d H:i:s') }}
        </div>

    </div>

</body>
</html>
