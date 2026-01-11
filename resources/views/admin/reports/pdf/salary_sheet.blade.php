<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Salary Sheet - {{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }} {{ $selectedYear }}</title>
    <style>
        /* General Settings */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #000; }
        .school-info { font-size: 10px; color: #555; }
        .report-title { font-size: 16px; font-weight: bold; text-transform: uppercase; text-align: right; }
        .report-period { font-size: 12px; color: #555; text-align: right; margin-top: 5px; }

        /* Summary Box */
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            background-color: #f9fafb;
        }
        .summary-box td { padding: 8px; text-align: center; width: 25%; border-right: 1px solid #e5e7eb; }
        .summary-box td:last-child { border-right: none; }
        .stat-label { display: block; font-size: 9px; text-transform: uppercase; color: #6b7280; font-weight: bold; }
        .stat-value { display: block; font-size: 14px; font-weight: bold; color: #111827; margin-top: 2px; }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .data-table th {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: bold;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #d1d5db;
            text-transform: uppercase;
            font-size: 9px;
        }
        .data-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 5px;
            color: #374151;
        }

        /* Row Striping */
        .data-table tr:nth-child(even) { background-color: #f9fafb; }

        /* Typography & Alignment */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'Courier New', Courier, monospace; } /* For numbers */
        .font-bold { font-weight: bold; }
        .text-red { color: #dc2626; }
        .text-green { color: #059669; }

        /* Grand Total Row */
        .total-row td {
            background-color: #f3f4f6;
            border-top: 2px solid #9ca3af;
            font-weight: bold;
            color: #000;
        }

        /* Signatures Footer */
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 0 auto;
            width: 80%;
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
        }

        /* Footer Info */
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
                <div class="report-title">Monthly Salary Sheet</div>
                <div class="report-period">
                    Period: <strong>{{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }} {{ $selectedYear }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-box">
        <tr>
            <td>
                <span class="stat-label">Total Staff</span>
                <span class="stat-value">{{ $payrolls->count() }}</span>
            </td>
            <td>
                <span class="stat-label">Total Earnings</span>
                <span class="stat-value">{{ number_format($totals['basic'] + $totals['allowances'], 2) }}</span>
            </td>
            <td>
                <span class="stat-label">Total Deductions</span>
                <span class="stat-value text-red">{{ number_format($totals['deductions'], 2) }}</span>
            </td>
            <td style="background-color: #ecfdf5;">
                <span class="stat-label" style="color: #047857;">Net Payout</span>
                <span class="stat-value text-green">{{ number_format($totals['net'], 2) }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Staff Name</th>
                <th width="15%">Designation</th>
                <th width="13%" class="text-right">Basic Salary</th>
                <th width="13%" class="text-right">Allowances</th>
                <th width="13%" class="text-right">Deductions</th>
                <th width="16%" class="text-right">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                <tr>
                    <td class="text-center">{{ $payroll->staff_id }}</td>
                    <td>
                        <span class="font-bold">{{ $payroll->staff->user->name }}</span>
                    </td>
                    <td>{{ $payroll->staff->designation }}</td>
                    <td class="text-right font-mono">{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="text-right font-mono">{{ number_format($payroll->total_allowances, 2) }}</td>
                    <td class="text-right font-mono text-red">{{ number_format($payroll->total_deductions, 2) }}</td>
                    <td class="text-right font-mono font-bold">{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">GRAND TOTALS:</td>
                <td class="text-right font-mono">{{ number_format($totals['basic'], 2) }}</td>
                <td class="text-right font-mono">{{ number_format($totals['allowances'], 2) }}</td>
                <td class="text-right font-mono text-red">{{ number_format($totals['deductions'], 2) }}</td>
                <td class="text-right font-mono text-green" style="font-size: 11px;">{{ number_format($totals['net'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="signature-section">
        <tr>
            <td class="signature-box">
                <div class="signature-line">Prepared By</div>
            </td>
            <td class="signature-box">
                <div class="signature-line">Checked By</div>
            </td>
            <td class="signature-box">
                <div class="signature-line">Approved By</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Confidential Financial Document | Generated on {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
