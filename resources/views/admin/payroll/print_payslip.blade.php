<!DOCTYPE html>
<html>
<head>
    <title>Payslip #{{ $payroll->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Print කරනවිට no-print class එක ඇති දේවල් හංගන්න */
        @media print {
            .no-print { display: none !important; }

                @page {
                margin: 0;
                size: auto;
            }
            /* 2. කඩදාසියේ වටේට ඉඩක් තැබීම (නැත්නම් අකුරු කැපෙන්න පුළුවන්) */
            body {
                margin: 1cm; /* සෙ.මී. 1ක ඉඩක් */
            }
            /* Background colors මුද්‍රණය වීමට */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="bg-gray-100 p-10 font-sans">

    <div class="max-w-2xl mx-auto bg-white p-10 shadow-lg">

        <div class="text-center border-b pb-4 mb-6">
            @if(isset($schoolSettings) && $schoolSettings->logo_path)
                <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}" class="h-16 mx-auto mb-2">
            @endif
            <h1 class="text-2xl font-bold uppercase">{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
            <p class="text-sm text-gray-600">{{ $schoolSettings->school_address ?? '' }}</p>
            <h2 class="text-xl font-semibold mt-4">PAYSLIP</h2>
            <p class="text-gray-500">{{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</p>
        </div>

        <div class="flex justify-between mb-6">
            <div>
                <p class="text-xs text-gray-500 uppercase">Employee Name</p>
                <p class="font-bold text-lg">{{ $payroll->staff->user->name }}</p>
                <p class="text-sm">{{ $payroll->staff->designation }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase">Payslip ID</p>
                <p class="font-bold">#{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-500 uppercase mt-2">Status</p>
                <p class="font-bold {{ $payroll->status == 'paid' ? 'text-green-600' : 'text-orange-500' }}">
                    {{ ucfirst($payroll->status) }}
                </p>
            </div>
        </div>

        <table class="w-full mb-6 border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left border">Description</th>
                    <th class="p-2 text-right border">Earnings</th>
                    <th class="p-2 text-right border">Deductions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border">Basic Salary</td>
                    <td class="p-2 border text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="p-2 border text-right">-</td>
                </tr>
                <tr>
                    <td class="p-2 border">Allowances (Total)</td>
                    <td class="p-2 border text-right">{{ number_format($payroll->total_allowances, 2) }}</td>
                    <td class="p-2 border text-right">-</td>
                </tr>
                <tr>
                    <td class="p-2 border">Deductions (Total)</td>
                    <td class="p-2 border text-right">-</td>
                    <td class="p-2 border text-right text-red-600">{{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
                <tr class="font-bold bg-gray-50">
                    <td class="p-2 border text-right">NET SALARY</td>
                    <td class="p-2 border text-right" colspan="2">{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mb-6">
            <h4 class="text-sm font-bold border-b pb-1 mb-2">Payment History</h4>
            @foreach($payroll->payments as $payment)
                <div class="flex justify-between text-sm">
                    <span>{{ $payment->payment_date }} ({{ ucfirst($payment->method) }})</span>
                    <span>LKR {{ number_format($payment->amount, 2) }}</span>
                </div>
            @endforeach
            <div class="flex justify-between font-bold border-t mt-2 pt-1">
                <span>Total Paid:</span>
                <span>LKR {{ number_format($payroll->paid_amount, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-red-600">
                <span>Balance Due:</span>
                <span>LKR {{ number_format($payroll->net_salary - $payroll->paid_amount, 2) }}</span>
            </div>
        </div>

        <div class="text-center mt-12 no-print flex justify-center gap-4">

            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 shadow-md transition duration-150">
                Print Payslip
            </button>

            <button onclick="window.close()" class="bg-gray-500 text-white px-6 py-2 rounded font-bold hover:bg-gray-600 shadow-md transition duration-150">
                Close
            </button>

        </div>
        </div>
</body>
</html>
