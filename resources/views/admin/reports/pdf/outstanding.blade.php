<!DOCTYPE html>
<html>
<head>
    <title>Outstanding Fees Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px; color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background-color: #e6e6e6; }
        .badge { background: #fee2e2; color: #991b1b; padding: 2px 5px; border-radius: 4px; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <p>{{ $schoolSettings->school_address ?? '' }}</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        <h2 style="margin-top: 15px; border-bottom: 1px solid #000; display: inline-block;">OUTSTANDING FEES REPORT</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Inv #</th>
                <th>Due Date</th>
                <th class="text-right">Balance (LKR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>
                        {{ $invoice->student->name }} <br>
                        <small style="color: #666;">{{ $invoice->student->admission_no }}</small>
                    </td>
                    <td>{{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}</td>
                    <td>#{{ $invoice->id }}</td>
                    <td>
                        {{ $invoice->due_date }}
                        @if($invoice->due_date < now()->format('Y-m-d'))
                            <span class="badge">Overdue</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tr class="total-row">
            <td colspan="4" class="text-right">TOTAL OUTSTANDING</td>
            <td class="text-right">{{ number_format($totalOutstanding, 2) }}</td>
        </tr>
    </table>

</body>
</html>
