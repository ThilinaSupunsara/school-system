<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Outstanding Fees Report</title>
    <style>
        /* General Settings */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #b91c1c; /* Red line for urgency */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #1a202c; }
        .school-info { font-size: 10px; color: #718096; }
        .report-title { font-size: 16px; font-weight: bold; color: #b91c1c; text-transform: uppercase; text-align: right; }
        .report-meta { font-size: 10px; color: #4a5568; text-align: right; margin-top: 5px; }

        /* Summary Box */
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            background-color: #fef2f2; /* Light red background */
        }
        .summary-box td { padding: 12px; text-align: center; width: 50%; border-right: 1px solid #e5e7eb; }
        .summary-box td:last-child { border-right: none; }
        .stat-label { display: block; font-size: 10px; text-transform: uppercase; color: #7f1d1d; font-weight: bold; }
        .stat-value { display: block; font-size: 18px; font-weight: bold; color: #b91c1c; margin-top: 2px; }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            border-bottom: 2px solid #d1d5db;
            text-transform: uppercase;
            font-size: 9px;
        }
        .data-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px;
            color: #1f2937;
            vertical-align: top;
        }

        /* Typography & Alignment */
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .font-bold { font-weight: bold; }
        .text-red { color: #dc2626; }
        .text-gray { color: #6b7280; }

        /* Overdue Badge */
        .badge-overdue {
            color: #dc2626;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            display: inline-block;
            margin-left: 5px;
        }

        /* Grand Total Row */
        .total-row td {
            background-color: #fee2e2;
            border-top: 2px solid #b91c1c;
            font-weight: bold;
            color: #7f1d1d;
            padding: 10px 8px;
            font-size: 11px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="60%">
                <div class="school-name">{{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}</div>
                <div class="school-info">
                    {{ $schoolSettings->school_address ?? 'Address Line 1' }}<br>
                    {{ $schoolSettings->phone ?? '' }}
                </div>
            </td>
            <td width="40%" align="right" valign="bottom">
                <div class="report-title">Outstanding Fees Report</div>
                <div class="report-meta">
                    Generated on: <strong>{{ now()->format('Y-m-d H:i:s') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-box">
        <tr>
            <td>
                <span class="stat-label">Pending Invoices</span>
                <span class="stat-value" style="color: #374151;">{{ $invoices->count() }}</span>
            </td>
            <td>
                <span class="stat-label">Total Amount Due</span>
                <span class="stat-value">{{ number_format($totalOutstanding, 2) }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30%">Student Details</th>
                <th width="15%">Class</th>
                <th width="15%">Invoice #</th>
                <th width="20%">Due Date</th>
                <th width="20%" class="text-right">Balance Due (LKR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>
                        <span class="font-bold">{{ $invoice->student->name }}</span><br>
                        <span class="text-gray" style="font-size: 9px;">ID: {{ $invoice->student->admission_no }}</span>
                    </td>
                    <td>
                        {{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}
                    </td>
                    <td>#{{ $invoice->id }}</td>
                    <td>
                        {{ $invoice->due_date }}
                        @if($invoice->due_date < now()->format('Y-m-d'))
                            <br><span class="badge-overdue">Overdue</span>
                        @endif
                    </td>
                    <td class="text-right font-mono font-bold text-red">
                        {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL OUTSTANDING:</td>
                <td class="text-right font-mono">{{ number_format($totalOutstanding, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Confidential Financial Document | Generated by System
    </div>

</body>
</html>
