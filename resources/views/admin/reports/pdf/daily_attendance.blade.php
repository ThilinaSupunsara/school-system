<!DOCTYPE html>
<html>
<head>
    <title>Daily Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px; color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-left { text-align: left; }
        .total-row td { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <p>{{ $schoolSettings->school_address ?? '' }}</p>
        <h2 style="margin-top: 15px; border-bottom: 1px solid #000; display: inline-block;">
            DAILY ATTENDANCE: {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
        </h2>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Class</th>
                <th>Total Students</th>
                <th>Present</th>
                <th>Late</th>
                <th>Absent</th>
                <th>Not Marked</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
                <tr>
                    <td class="text-left">{{ $row->grade }} - {{ $row->section }}</td>
                    <td>{{ $row->total }}</td>
                    <td style="color: green;">{{ $row->present }}</td>
                    <td style="color: orange;">{{ $row->late }}</td>
                    <td style="color: red;">{{ $row->absent }}</td>
                    <td style="color: gray;">
                        {{ $row->not_marked > 0 ? $row->not_marked : '-' }}
                    </td>
                    <td>{{ $row->percentage }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tr class="total-row">
            <td class="text-left">TOTALS</td>
            <td>{{ $reportData->sum('total') }}</td>
            <td style="color: green;">{{ $reportData->sum('present') }}</td>
            <td style="color: orange;">{{ $reportData->sum('late') }}</td>
            <td style="color: red;">{{ $reportData->sum('absent') }}</td>
            <td>{{ $reportData->sum('not_marked') }}</td>
            <td>
                @php
                    $grandTotal = $reportData->sum('total');
                    $grandPresent = $reportData->sum('present') + $reportData->sum('late');
                    $grandPct = $grandTotal > 0 ? round(($grandPresent / $grandTotal) * 100, 1) : 0;
                @endphp
                {{ $grandPct }}%
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #777;">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
