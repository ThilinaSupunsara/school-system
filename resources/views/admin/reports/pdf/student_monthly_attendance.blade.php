<!DOCTYPE html>
<html>
<head>
    <title>Student Monthly Attendance</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }

        .student-info { margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; }
        .student-info h3 { margin: 0 0 5px 0; }

        .summary-box { width: 100%; margin-bottom: 20px; }
        .summary-box td { text-align: center; padding: 10px; background: #f9f9f9; border: 1px solid #eee; }
        .stat-value { font-size: 16px; font-weight: bold; display: block; }
        .stat-label { font-size: 10px; color: #666; text-transform: uppercase; }

        table.records { width: 100%; border-collapse: collapse; }
        table.records th, table.records td { border: 1px solid #ccc; padding: 5px; text-align: center; }
        table.records th { background-color: #eee; }

        .text-green { color: green; font-weight: bold; }
        .text-red { color: red; font-weight: bold; }
        .text-orange { color: orange; font-weight: bold; }
        .weekend { background-color: #f5f5f5; color: #999; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <p>{{ $schoolSettings->school_address ?? '' }}</p>
        <h3 style="margin-top: 10px; border-bottom: 1px solid #000; display: inline-block;">
            MONTHLY REPORT: {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
        </h3>
    </div>

    <div class="student-info">
        <h3>{{ $selectedStudent->name }}</h3>
        <p>
            <strong>Admission No:</strong> {{ $selectedStudent->admission_no }} <br>
            <strong>Class:</strong> {{ $selectedStudent->section->grade->name }} - {{ $selectedStudent->section->name }}
        </p>
    </div>

    <table class="summary-box">
        <tr>
            <td>
                <span class="stat-value text-green">{{ $summary['present'] }}</span>
                <span class="stat-label">Present</span>
            </td>
            <td>
                <span class="stat-value text-orange">{{ $summary['late'] }}</span>
                <span class="stat-label">Late</span>
            </td>
            <td>
                <span class="stat-value text-red">{{ $summary['absent'] }}</span>
                <span class="stat-label">Absent</span>
            </td>
            <td>
                <span class="stat-value">{{ $summary['percentage'] }}%</span>
                <span class="stat-label">Attendance Rate</span>
            </td>
        </tr>
    </table>

    <table class="records">
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateString = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                    $dateObj = \Carbon\Carbon::parse($dateString);
                    $isWeekend = $dateObj->isWeekend();
                    $record = $attendanceRecords->get($dateString);
                @endphp

                <tr class="{{ $isWeekend ? 'weekend' : '' }}">
                    <td>{{ $dateString }}</td>
                    <td>{{ $dateObj->format('l') }}</td>
                    <td>
                        @if ($record)
                            @if ($record->status == 'present') <span class="text-green">Present</span>
                            @elseif ($record->status == 'absent') <span class="text-red">Absent</span>
                            @elseif ($record->status == 'late') <span class="text-orange">Late</span>
                            @endif
                        @else
                            @if ($isWeekend) - @else <span style="color: #ccc; font-style: italic;">Not Marked</span> @endif
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 10px; color: #777; text-align: center;">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
