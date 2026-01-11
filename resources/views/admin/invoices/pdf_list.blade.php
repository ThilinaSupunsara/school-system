<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoices Report</title>
    <style>
        /* General Settings */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Header Layout (Using Table for PDF compatibility) */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2d3748; /* Dark Gray Line */
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-left { text-align: left; vertical-align: top; }
        .header-right { text-align: right; vertical-align: bottom; }

        .school-name { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #1a202c; margin: 0; }
        .school-info { font-size: 10px; color: #718096; margin: 0; }

        .report-title { font-size: 22px; font-weight: bold; color: #2d3748; text-transform: uppercase; margin: 0; }
        .meta-info { font-size: 10px; color: #718096; margin-top: 5px; }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px;
            border-bottom: 1px solid #cbd5e0;
            text-align: left;
        }

        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        /* Zebra Striping (Optional, looks good in print) */
        .data-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* Specific Column Alignments */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }

        /* Status Colors (Text only is safer for PDF than badges) */
        .status-paid { color: #047857; font-weight: bold; } /* Green */
        .status-partial { color: #d69e2e; font-weight: bold; } /* Orange/Gold */
        .status-pending { color: #e53e3e; font-weight: bold; } /* Red */

        /* Totals Section */
        .grand-total-row td {
            background-color: #edf2f7;
            border-top: 2px solid #4a5568;
            font-weight: bold;
            font-size: 12px;
            padding: 10px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 9px;
            color: #a0aec0;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                {{-- <img src="{{ public_path('storage/'.$schoolSettings->logo_path) }}" style="height: 50px; margin-bottom: 5px;"> --}}

                <h1 class="school-name">{{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}</h1>
                <p class="school-info">
                    {{ $schoolSettings->school_address ?? 'Address Line 1, City' }}<br>
                    {{ $schoolSettings->phone ?? '011-2345678' }} | {{ $schoolSettings->email ?? 'info@school.com' }}
                </p>
            </td>
            <td class="header-right">
                <h2 class="report-title">Invoices Report</h2>
                <div class="meta-info">
                    <strong>Generated On:</strong> {{ now()->format('d M Y, h:i A') }}<br>
                    <strong>Total Records:</strong> {{ $invoices->count() }}
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50px;">Inv #</th>
                <th>Student Details</th>
                <th>Grade/Class</th>
                <th style="width: 70px;">Date</th>
                <th style="width: 60px;">Status</th>
                <th class="text-right" style="width: 80px;">Total</th>
                <th class="text-right" style="width: 80px;">Paid</th>
                <th class="text-right" style="width: 80px;">Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td class="font-mono">#{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <strong>{{ $invoice->student->name }}</strong><br>
                        <span style="font-size: 9px; color: #718096;">Reg: {{ $invoice->student->admission_no }}</span>
                    </td>
                    <td>
                        {{ $invoice->student->section->grade->name }} - {{ $invoice->student->section->name }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                    <td>
                        @if($invoice->status == 'paid')
                            <span class="status-paid">PAID</span>
                        @elseif($invoice->status == 'partial')
                            <span class="status-partial">PARTIAL</span>
                        @else
                            <span class="status-pending">PENDING</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="grand-total-row">
                <td colspan="5" class="text-right">GRAND TOTALS:</td>
                <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
                <td class="text-right">{{ number_format($totalPaid, 2) }}</td>
                <td class="text-right">{{ number_format($totalDue, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Confidential Report - Generated by System
    </div>

</body>
</html>
