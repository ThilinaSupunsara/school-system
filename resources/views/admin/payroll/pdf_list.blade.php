<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payroll Summary Report</title>
    <style>
        /* General Settings */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px; /* Slightly smaller for detailed table */
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-left { text-align: left; vertical-align: top; }
        .header-right { text-align: right; vertical-align: bottom; }

        .school-name { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1a202c; margin: 0; }
        .school-info { font-size: 9px; color: #718096; margin: 0; }

        .report-title { font-size: 18px; font-weight: bold; color: #2d3748; text-transform: uppercase; margin: 0; }
        .meta-info { font-size: 9px; color: #718096; margin-top: 5px; }

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
            font-size: 8px;
            padding: 6px;
            border-bottom: 1px solid #cbd5e0;
            text-align: left;
        }

        .data-table td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
            font-size: 9px;
        }

        .data-table tr:nth-child(even) { background-color: #fcfcfc; }

        /* Alignments */
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Financial Columns width control */
        .col-money { width: 12%; }

        /* Status Colors */
        .status-paid { color: #047857; font-weight: bold; }
        .status-generated { color: #2b6cb0; font-weight: bold; }

        /* Totals Row */
        .grand-total-row td {
            background-color: #edf2f7;
            border-top: 2px solid #4a5568;
            font-weight: bold;
            font-size: 10px;
            padding: 8px 6px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            font-size: 8px;
            color: #a0aec0;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <h1 class="school-name">{{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}</h1>
                <p class="school-info">
                    {{ $schoolSettings->school_address ?? 'Address Line 1' }}<br>
                    {{ $schoolSettings->phone ?? '' }}
                </p>
            </td>
            <td class="header-right">
                <h2 class="report-title">Payroll Report</h2>
                <div class="meta-info">
                    Generated: {{ now()->format('d M Y, h:i A') }}<br>
                    Records: {{ $payrolls->count() }}
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Designation</th>
                <th>Period</th>
                <th>Status</th>
                <th class="text-right col-money">Basic</th>
                <th class="text-right col-money">Allowances</th>
                <th class="text-right col-money">Deductions</th>
                <th class="text-right col-money">Net Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->staff->user->name }}</td>
                    <td>{{ $payroll->staff->designation }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($payroll->month)->format('M') }} {{ $payroll->year }}</td>
                    <td>
                        @if($payroll->status == 'paid')
                            <span class="status-paid">PAID</span>
                        @else
                            <span class="status-generated">GENERATED</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="text-right">{{ number_format($payroll->total_allowances, 2) }}</td>
                    <td class="text-right">{{ number_format($payroll->total_deductions, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="grand-total-row">
                <td colspan="4" class="text-right">GRAND TOTALS:</td>
                <td class="text-right">{{ number_format($totalBasic, 2) }}</td>
                <td class="text-right">{{ number_format($payrolls->sum('total_allowances'), 2) }}</td>
                <td class="text-right">{{ number_format($payrolls->sum('total_deductions'), 2) }}</td>
                <td class="text-right">{{ number_format($totalNetSalary, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Confidential Financial Report - Generated by System
    </div>

</body>
</html>
