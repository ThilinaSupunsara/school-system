<!DOCTYPE html>
<html>
<head>
    <title>Payroll Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .status-paid { color: green; font-weight: bold; }
        .status-generated { color: blue; }
        .total-row td { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <p>{{ $schoolSettings->school_address ?? '' }}</p>
        <h2 style="margin-top: 15px; border-bottom: 1px solid #000; display: inline-block;">
            PAYROLL SUMMARY REPORT
        </h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Designation</th>
                <th>Month/Year</th>
                <th>Status</th>
                <th class="text-right">Basic Salary</th>
                <th class="text-right">Allowances</th>
                <th class="text-right">Deductions</th>
                <th class="text-right">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->staff->user->name }}</td>
                    <td>{{ $payroll->staff->designation }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($payroll->month)->format('M') }} / {{ $payroll->year }}</td>
                    <td>
                        @if($payroll->status == 'paid') <span class="status-paid">Paid</span>
                        @else <span class="status-generated">Generated</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="text-right">{{ number_format($payroll->total_allowances, 2) }}</td>
                    <td class="text-right">{{ number_format($payroll->total_deductions, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tr class="total-row">
            <td colspan="4" class="text-right">TOTALS</td>
            <td class="text-right">{{ number_format($totalBasic, 2) }}</td>
            <td class="text-right">{{ number_format($payrolls->sum('total_allowances'), 2) }}</td>
            <td class="text-right">{{ number_format($payrolls->sum('total_deductions'), 2) }}</td>
            <td class="text-right">{{ number_format($totalNetSalary, 2) }}</td>
        </tr>
    </table>

</body>
</html>
