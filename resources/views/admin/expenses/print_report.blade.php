<!DOCTYPE html>
<html>
<head>
    <title>Expenses Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
            /* අලුත් පිටුවකට යන්න ඕන තැන් */
            .page-break { page-break-before: always; }
            /* රිසිට් එකක් මැදින් කැඩෙන එක නවත්වන්න */
            .receipt-box { break-inside: avoid; page-break-inside: avoid; }
        }
        @page {
                margin: 0;
                size: auto;
            }
    </style>
</head>
<body class="bg-white p-8 text-sm font-sans text-gray-800">

    <div class="text-center mb-6 border-b pb-4">
        <h1 class="text-2xl font-bold uppercase">{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <h2 class="text-xl font-semibold mt-2">EXPENSES SUMMARY REPORT</h2>
        <p class="text-gray-500 text-xs">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table class="w-full border-collapse border border-gray-300 mb-8">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2 text-left w-12">#ID</th>
                <th class="border p-2 text-left">Date</th>
                <th class="border p-2 text-left">Description</th>
                <th class="border p-2 text-left">Recipient</th>
                <th class="border p-2 text-right">Amount (LKR)</th>
                <th class="border p-2 text-center">Receipt Ref</th> </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr class="border-b">
                    <td class="border p-2 text-gray-500">{{ $expense->id }}</td>
                    <td class="border p-2">{{ $expense->created_at->format('Y-m-d') }}</td>
                    <td class="border p-2">
                        <span class="font-bold block text-xs uppercase text-gray-500">{{ $expense->category->name ?? 'General' }}</span>
                        {{ $expense->description }}
                    </td>
                    <td class="border p-2">{{ $expense->recipient_name }}</td>
                    <td class="border p-2 text-right font-bold">
                        {{ $expense->amount_spent ? number_format($expense->amount_spent, 2) : number_format($expense->amount_given, 2) . ' (Adv)' }}
                    </td>

                    <td class="border p-2 text-center">
                        @if($expense->receipt_path)
                            <span class="text-xs font-bold bg-gray-200 px-2 py-1 rounded">See Annex #{{ $expense->id }}</span>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr class="bg-gray-100 font-bold">
                <td colspan="4" class="border p-2 text-right">TOTAL SPENT</td>
                <td class="border p-2 text-right">{{ number_format($expenses->sum('amount_spent'), 2) }}</td>
                <td class="border p-2"></td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="mt-8">
        <h2 class="text-xl font-bold border-b-2 border-black pb-2 mb-6">ANNEXURES (RECEIPT PROOFS)</h2>

        <div class="grid grid-cols-1 gap-8">
            @foreach($expenses as $expense)
                @if($expense->receipt_path)

                    <div class="receipt-box border border-gray-300 rounded-lg p-4 bg-gray-50 shadow-sm">

                        <div class="flex justify-between items-center border-b pb-2 mb-4">
                            <h3 class="font-bold text-lg">Annex #{{ $expense->id }}</h3>
                            <div class="text-right">
                                <p class="text-sm font-bold">{{ $expense->description }}</p>
                                <p class="text-xs text-gray-500">Date: {{ $expense->created_at->format('Y-m-d') }} | Amount: {{ number_format($expense->amount_spent, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $expense->receipt_path) }}"
                                 class="max-h-[500px] max-w-full object-contain border"
                                 alt="Receipt for {{ $expense->description }}">
                        </div>

                    </div>

                    <div class="h-8"></div>

                @endif
            @endforeach
        </div>

        @if($expenses->whereNotNull('receipt_path')->count() == 0)
            <p class="text-center text-gray-500 italic mt-10">No receipts attached for this report.</p>
        @endif
    </div>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t p-4 text-center no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Print Full Report</button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-6 py-2 rounded font-bold hover:bg-gray-600 ml-2">Close</button>
    </div>

</body>
</html>
