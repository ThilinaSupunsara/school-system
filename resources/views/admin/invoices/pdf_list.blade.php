<!DOCTYPE html>
<html>
<head>
    <title>Invoices Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .status-paid { color: green; font-weight: bold; }
        .status-pending { color: red; }
        .status-partial { color: orange; }
        .total-row td { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <p>{{ $schoolSettings->school_address ?? '' }}</p>
        <h2 style="margin-top: 15px; border-bottom: 1px solid #000; display: inline-block;">
            INVOICES REPORT
        </h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Inv #</th>
                <th>Student</th>
                <th>Class</th>
                <th>Date</th>
                <th>Status</th>
                <th class="text-right">Total</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->student->name }}</td>
                    <td>{{ $invoice->student->section->grade->name }}-{{ $invoice->student->section->name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>
                        @if($invoice->status == 'paid') <span class="status-paid">Paid</span>
                        @elseif($invoice->status == 'partial') <span class="status-partial">Partial</span>
                        @else <span class="status-pending">Pending</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tr class="total-row">
            <td colspan="5" class="text-right">GRAND TOTALS</td>
            <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
            <td class="text-right">{{ number_format($totalPaid, 2) }}</td>
            <td class="text-right">{{ number_format($totalDue, 2) }}</td>
        </tr>
    </table>

</body>
</html>
