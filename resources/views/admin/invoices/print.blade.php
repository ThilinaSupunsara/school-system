<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6; /* Gray-100 for screen */
        }

        .invoice-container {
            background: white;
            width: 210mm; /* A4 Width */
            min-height: 297mm; /* A4 Height */
            margin: 2rem auto;
            padding: 15mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        @media print {
            body {
                background: white;
                margin: 0;
            }
            .invoice-container {
                width: 100%;
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                border: none;
                min-height: auto;
            }
            .no-print {
                display: none !important;
            }
            /* Ensure background colors print */
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
            <h2 class="font-bold text-gray-800">Print Preview</h2>
            <p class="text-xs text-gray-500">Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.close()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition">
                Close
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Invoice
            </button>
        </div>
    </div>

    <div class="h-20 no-print"></div> <div class="invoice-container rounded-none sm:rounded-sm">

        <div class="flex justify-between items-start border-b border-gray-200 pb-8">
            <div class="flex items-start gap-4">
                @if(isset($schoolSettings) && $schoolSettings->logo_path)
                    <img src="{{ asset('storage/' . $schoolSettings->logo_path) }}" alt="Logo" class="h-24 w-auto object-contain">
                @endif
                <div class="mt-1">
                    <h1 class="text-xl font-bold text-gray-900 uppercase tracking-wide">
                        {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
                    </h1>
                    <div class="text-sm text-gray-500 mt-1 space-y-0.5">
                        <p>{{ $schoolSettings->school_address ?? 'School Address' }}</p>
                        <p>{{ $schoolSettings->phone ?? 'Phone Number' }}</p>
                        <p>{{ $schoolSettings->email ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-extrabold text-gray-200 uppercase tracking-widest">Invoice</h2>
                <p class="text-gray-600 font-bold mt-1">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>

                <div class="mt-2">
                    @if($invoice->status == 'paid')
                        <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded border border-green-200 text-xs font-bold uppercase tracking-wider">PAID</span>
                    @elseif($invoice->status == 'partial')
                        <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded border border-yellow-200 text-xs font-bold uppercase tracking-wider">PARTIAL</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded border border-red-200 text-xs font-bold uppercase tracking-wider">UNPAID</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-8 mb-10">
            <div class="w-1/2">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Billed To</h3>
                <div class="text-gray-800">
                    <p class="font-bold text-lg">{{ $invoice->student->name }}</p>
                    <p class="text-sm">Adm No: <span class="font-mono font-medium">{{ $invoice->student->admission_no }}</span></p>
                    <p class="text-sm mt-1">{{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $invoice->student->parent_name ?? '' }}</p>
                </div>
            </div>
            <div class="w-1/3 text-right">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Invoice Date:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $invoice->invoice_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Due Date:</span>
                        <span class="text-sm font-bold {{ $invoice->due_date < now() && $invoice->status != 'paid' ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $invoice->due_date }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider rounded-l-lg">Description</th>
                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider rounded-r-lg">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->invoiceItems as $item)
                <tr class="border-b border-gray-100 last:border-b-0">
                    <td class="py-3 px-4 text-sm text-gray-700">{{ $item->feeCategory->name }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-medium text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-between items-start">

            <div class="w-1/2 pr-8">
                @if($invoice->payments->count() > 0)
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Payment History</h4>
                    <table class="w-full text-xs">
                        @foreach($invoice->payments as $payment)
                        <tr class="border-b border-gray-50">
                            <td class="py-1 text-gray-500">{{ $payment->payment_date }}</td>
                            <td class="py-1 text-gray-500 capitalize">{{ $payment->method }}</td>
                            <td class="py-1 text-gray-700 text-right">{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </table>
                @endif
            </div>

            <div class="w-5/12">
                <div class="space-y-2">
                    <div class="flex justify-between py-1">
                        <span class="text-sm font-medium text-gray-500">Sub Total:</span>
                        <span class="text-sm font-bold text-gray-800">{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-200 pb-2">
                        <span class="text-sm font-medium text-green-600">Paid Amount:</span>
                        <span class="text-sm font-bold text-green-600">(-) {{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-base font-bold text-gray-800">Total Due:</span>
                        <span class="text-lg font-extrabold text-blue-700">LKR {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20 pt-8 border-t border-gray-200">
            <div class="flex justify-between items-end">
                <div class="text-center">
                    <div class="w-40 border-b border-gray-400 mb-2"></div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Student/Parent Signature</p>
                </div>
                <div class="text-center">
                    <div class="w-40 border-b border-gray-400 mb-2"></div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Authorized Signature</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">Thank you for your timely payment.</p>
                <p class="text-[10px] text-gray-300 mt-1">Generated by System on {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>

    </div>

    <script>
        // Optional: Auto print when page loads
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
