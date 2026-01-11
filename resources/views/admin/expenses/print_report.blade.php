<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Report - {{ now()->format('Y-m-d') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
        }

        .report-page {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 2rem auto;
            padding: 15mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .page-break {
            page-break-before: always;
        }

        .receipt-container {
            break-inside: avoid; /* Prevent breaking inside an element */
            page-break-inside: avoid;
        }

        @media print {
            body {
                background: white;
                margin: 0;
            }
            .report-page {
                width: 100%;
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                min-height: auto;
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
            <h2 class="font-bold text-gray-800">Expenses Report Preview</h2>
            <p class="text-xs text-gray-500">Includes summary table and attached receipt images.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.close()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition">
                Close
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <div class="h-20 no-print"></div>

    <div class="report-page">

        <div class="text-center border-b border-gray-200 pb-6 mb-6">
            <h1 class="text-xl font-bold text-gray-900 uppercase tracking-wide">
                {{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}
            </h1>
            <h2 class="text-lg font-bold text-gray-700 mt-2">EXPENSES SUMMARY REPORT</h2>
            <p class="text-xs text-gray-500 mt-1">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <table class="w-full text-xs border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300">
                    <th class="py-2 px-2 text-left font-bold text-gray-600 w-12">#ID</th>
                    <th class="py-2 px-2 text-left font-bold text-gray-600 w-20">Date</th>
                    <th class="py-2 px-2 text-left font-bold text-gray-600">Description</th>
                    <th class="py-2 px-2 text-left font-bold text-gray-600 w-32">Recipient</th>
                    <th class="py-2 px-2 text-right font-bold text-gray-600 w-24">Amount (LKR)</th>
                    <th class="py-2 px-2 text-center font-bold text-gray-600 w-20">Annex</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr class="border-b border-gray-200">
                    <td class="py-2 px-2 text-gray-500">{{ $expense->id }}</td>
                    <td class="py-2 px-2">{{ $expense->created_at->format('Y-m-d') }}</td>
                    <td class="py-2 px-2">
                        <span class="block font-bold text-gray-700">{{ $expense->category->name ?? 'General' }}</span>
                        <span class="text-gray-600">{{ $expense->description }}</span>
                    </td>
                    <td class="py-2 px-2">{{ $expense->recipient_name }}</td>
                    <td class="py-2 px-2 text-right font-medium">
                        @if($expense->status == 'completed')
                            {{ number_format($expense->amount_spent, 2) }}
                        @else
                            <span class="text-gray-400 italic">{{ number_format($expense->amount_given, 2) }} (Adv)</span>
                        @endif
                    </td>
                    <td class="py-2 px-2 text-center">
                        @if($expense->receipt_path)
                            <span class="bg-gray-100 text-gray-600 px-1 py-0.5 rounded border border-gray-300 text-[10px]">#{{ $expense->id }}</span>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-300">
                    <td colspan="4" class="py-3 px-2 text-right font-bold text-gray-800 uppercase">Total Expenses</td>
                    <td class="py-3 px-2 text-right font-bold text-gray-900">{{ number_format($expenses->sum('amount_spent'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-12 flex justify-between items-end pt-4 border-t border-gray-200">
            <div class="text-center w-40">
                <div class="border-b border-gray-400 h-8 mb-1"></div>
                <p class="text-[10px] uppercase font-bold text-gray-500">Prepared By</p>
            </div>
            <div class="text-center w-40">
                <div class="border-b border-gray-400 h-8 mb-1"></div>
                <p class="text-[10px] uppercase font-bold text-gray-500">Checked By</p>
            </div>
            <div class="text-center w-40">
                <div class="border-b border-gray-400 h-8 mb-1"></div>
                <p class="text-[10px] uppercase font-bold text-gray-500">Approved By</p>
            </div>
        </div>
    </div>

    @if($expenses->whereNotNull('receipt_path')->count() > 0)

        <div class="page-break"></div>

        <div class="report-page">
            <div class="border-b border-black pb-2 mb-6">
                <h2 class="text-lg font-bold text-gray-900 uppercase">Annexures: Receipt Proofs</h2>
            </div>

            <div class="space-y-8">
                @foreach($expenses as $expense)
                    @if($expense->receipt_path)
                        <div class="receipt-container border border-gray-300 rounded-lg p-4 bg-gray-50">

                            <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-4">
                                <span class="bg-black text-white px-2 py-1 rounded text-xs font-bold">Annex #{{ $expense->id }}</span>
                                <div class="text-right text-xs">
                                    <span class="font-bold text-gray-800 block">{{ $expense->description }}</span>
                                    <span class="text-gray-500">{{ $expense->created_at->format('Y-m-d') }} | LKR {{ number_format($expense->amount_spent, 2) }}</span>
                                </div>
                            </div>

                            <div class="flex justify-center bg-white p-2 border border-gray-200 rounded">
                                <img src="{{ asset('storage/' . $expense->receipt_path) }}"
                                     alt="Receipt"
                                     class="max-h-[8cm] max-w-full object-contain">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

</body>
</html>
