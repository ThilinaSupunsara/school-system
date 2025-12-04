<!DOCTYPE html>
<html>
<head>
    <title>Monthly Salary Sheet</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px; color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <p>{{ $schoolSettings->school_address ?? '' }}</p>
        <h2 style="margin-top: 15px; border-bottom: 1px solid #000; display: inline-block;">
            SALARY SHEET: {{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }} {{ $selectedYear }}
        </h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Staff Name</th>
                <th>Designation</th>
                <th class="text-right">Basic</th>
                <th class="text-right">Allowances</th>
                <th class="text-right">Deductions</th>
                <th class="text-right">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->staff_id }}</td>
                    <td>{{ $payroll->staff->user->name }}</td>
                    <td>{{ $payroll->staff->designation }}</td>
                    <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="text-right">{{ number_format($payroll->total_allowances, 2) }}</td>
                    <td class="text-right" style="color: red;">{{ number_format($payroll->total_deductions, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tr class="total-row">
            <td colspan="3" class="text-right">TOTALS</td>
            <td class="text-right">{{ number_format($totals['basic'], 2) }}</td>
            <td class="text-right">{{ number_format($totals['allowances'], 2) }}</td>
            <td class="text-right" style="color: red;">{{ number_format($totals['deductions'], 2) }}</td>
            <td class="text-right">{{ number_format($totals['net'], 2) }}</td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #777;">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
