<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Class Monthly Attendance</title>
    <style>
        /* Page Settings */
        @page {
            margin: 10mm;
            size: A4 landscape; /* දින 31ක් පෙන්වීමට Landscape වඩා හොඳයි */
        }

        body {
            font-family: sans-serif;
            font-size: 9px;
        }

        /* --- New Header Styles --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .header-table td {
            border: none; /* Header එකේ කොටු වලට ඉරි අයින් කරන්න */
            padding: 2px;
            vertical-align: bottom;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            text-transform: uppercase;
        }
        .meta-info {
            font-size: 10px;
            color: #333;
        }

        /* --- Your Old Table Styles (Scoped by class) --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .data-table th, .data-table td {
            border: 1px solid #444;
            padding: 2px;
            text-align: center;
        }
        .data-table th {
            background-color: #eee;
            font-weight: bold;
            height: 20px;
        }

        .name-col { text-align: left; padding-left: 5px; width: 150px; }
        .status-p { color: green; font-weight: bold; }
        .status-a { color: red; font-weight: bold; }
        .status-l { color: orange; font-weight: bold; }
        .weekend { background-color: #f0f0f0; }

        /* Summary Column Colors */
        .bg-green { background: #e0f2f1; }
        .bg-red { background: #fee2e2; }
        .bg-yellow { background: #fef3c7; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="text-align:left;">
                <div class="school-name">{{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}</div>
                <div class="meta-info">
                    {{ $schoolSettings->school_address ?? '' }} <br>
                    {{ $schoolSettings->phone ?? '' }}
                </div>
            </td>

            <td style="text-align:right;">
                <div class="report-title">MONTHLY ATTENDANCE REGISTER</div>
                <div class="meta-info">
                    Class: <strong>{{ $selectedSection->grade->name }} - {{ $selectedSection->name }}</strong> <br>
                    Month: <strong>{{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="name-col">Student Name</th>
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    <th>{{ $day }}</th>
                @endfor
                <th class="bg-green">P</th>
                <th class="bg-red">A</th>
                <th class="bg-yellow">L</th>
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

                    <td style="font-weight: bold;" class="bg-green">{{ $present }}</td>
                    <td style="font-weight: bold;" class="bg-red">{{ $absent }}</td>
                    <td style="font-weight: bold;" class="bg-yellow">{{ $late }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; border-top: 1px solid #ccc; padding-top: 5px;">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
