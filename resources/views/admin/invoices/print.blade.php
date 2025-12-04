<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            @page { margin: 0; size: auto; }
            body { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 font-sans">

    <div class="max-w-3xl mx-auto bg-white p-10 shadow-lg rounded-lg" id="invoice">

        <div class="flex justify-between items-center border-b pb-8">
            <div class="flex items-center gap-4">
                @if(isset($schoolSettings) && $schoolSettings->logo_path)
                    <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}" alt="Logo" class="h-20 w-auto object-contain">
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
            <div class="text-right">
                <h2 class="text-3xl font-bold text-gray-700">INVOICE</h2>
                <p class="text-gray-500">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                <div class="mt-2">
                    @if($invoice->status == 'paid')
                        <span class="border border-green-600 text-green-600 px-2 py-1 rounded text-xs font-bold uppercase">PAID</span>
                    @elseif($invoice->status == 'partial')
                        <span class="border border-yellow-600 text-yellow-600 px-2 py-1 rounded text-xs font-bold uppercase">PARTIAL</span>
                    @else
                        <span class="border border-red-600 text-red-600 px-2 py-1 rounded text-xs font-bold uppercase">UNPAID</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-8 mb-8">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Bill To:</h3>
                <p class="text-lg font-bold text-gray-800">{{ $invoice->student->name }}</p>
                <p class="text-sm text-gray-600">Adm No: {{ $invoice->student->admission_no }}</p>
                <p class="text-sm text-gray-600">Class: {{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}</p>
            </div>
            <div class="text-right">
                <div class="mb-2">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Invoice Date:</h3>
                    <p class="font-semibold">{{ $invoice->invoice_date }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Due Date:</h3>
                    <p class="font-semibold text-red-500">{{ $invoice->due_date }}</p>
                </div>
            </div>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-300">
                    <th class="text-left py-2 text-sm font-bold text-gray-600 uppercase">Description</th>
                    <th class="text-right py-2 text-sm font-bold text-gray-600 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->invoiceItems as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-4 text-gray-700">{{ $item->feeCategory->name }}</td>
                    <td class="py-4 text-right text-gray-700">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mb-8">
            <div class="w-1/2">
                <div class="flex justify-between py-2 border-b">
                    <span class="font-semibold text-gray-600">Total Amount:</span>
                    <span class="font-bold text-gray-800">LKR {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="font-semibold text-green-600">Paid Amount:</span>
                    <span class="font-bold text-green-600">LKR {{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="font-bold text-xl text-gray-800">Balance Due:</span>
                    <span class="font-bold text-xl text-red-600">LKR {{ number_format($balance, 2) }}</span>
                </div>
            </div>
        </div>

        @if($invoice->payments->count() > 0)
        <div class="mb-8">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b pb-1">Payment History</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 text-left">
                        <th class="pb-2 font-medium">Date</th>
                        <th class="pb-2 font-medium">Method</th>
                        <th class="pb-2 font-medium text-right">Amount Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr class="border-b border-gray-100">
                        <td class="py-2">{{ $payment->payment_date }}</td>
                        <td class="py-2 capitalize">{{ ucfirst($payment->method) }}</td>
                        <td class="py-2 text-right text-gray-700">LKR {{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        <div class="mt-12 pt-8 border-t text-center text-gray-500 text-sm">
            <p>Thank you for your payment.</p>
        </div>

        <div class="text-center mt-8 no-print flex justify-center gap-4">
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md">
                Print Invoice
            </button>
            <button onclick="window.close()" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-bold shadow-md">
                Close
            </button>
        </div>

    </div>
</body>
</html>
