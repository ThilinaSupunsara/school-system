<!DOCTYPE html>
<html>
<head>
    <title>Class Monthly Attendance</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; } /* Small font */
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 14px; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #444; padding: 2px; text-align: center; }
        th { background-color: #eee; font-weight: bold; height: 20px; }
        .name-col { text-align: left; padding-left: 5px; width: 150px; }
        .status-p { color: green; font-weight: bold; }
        .status-a { color: red; font-weight: bold; }
        .status-l { color: orange; font-weight: bold; }
        .weekend { background-color: #f0f0f0; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $schoolSettings->school_name ?? 'School Name' }}</h1>
        <h3>Class: {{ $selectedSection->grade->name }} - {{ $selectedSection->name }} | Month: {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th class="name-col">Student Name</th>
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    <th>{{ $day }}</th>
                @endfor
                <th style="background: #e0f2f1;">P</th>
                <th style="background: #fee2e2;">A</th>
                <th style="background: #fef3c7;">L</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                @php
                    $present = 0; $absent = 0; $late = 0;
                @endphp
                <tr>
                    <td class="name-col">{{ $student->name }}</td>

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                            $isWeekend = \Carbon\Carbon::parse($date)->isWeekend();
                            $status = $attendanceMatrix[$student->id][$date] ?? null;

                            if($status == 'present') $present++;
                            if($status == 'absent') $absent++;
                            if($status == 'late') $late++;
                        @endphp

                        <td class="{{ $isWeekend ? 'weekend' : '' }}">
                            @if ($status == 'present') <span class="status-p">P</span>
                            @elseif ($status == 'absent') <span class="status-a">A</span>
                            @elseif ($status == 'late') <span class="status-l">L</span>
                            @endif
                        </td>
                    @endfor

                    <td style="font-weight: bold;">{{ $present }}</td>
                    <td style="font-weight: bold;">{{ $absent }}</td>
                    <td style="font-weight: bold;">{{ $late }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
