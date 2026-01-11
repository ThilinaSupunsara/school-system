<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Attendance Report - {{ $selectedStudent->name }}</title>
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
            border-bottom: 2px solid #2d3748;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #1a202c; }
        .school-info { font-size: 10px; color: #718096; }
        .report-title { font-size: 16px; font-weight: bold; color: #2d3748; text-transform: uppercase; text-align: right; }

        /* Student Profile Box */
        .profile-box {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            padding: 10px;
            background-color: #f8fafc;
        }
        .profile-table td { padding: 5px; font-size: 11px; }
        .label { font-weight: bold; color: #4a5568; width: 100px; }

        /* Summary Statistics */
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-table td {
            border: 1px solid #cbd5e0;
            padding: 10px;
            text-align: center;
            width: 25%;
        }
        .stat-value { font-size: 18px; font-weight: bold; display: block; margin-bottom: 5px; }
        .stat-label { font-size: 9px; text-transform: uppercase; color: #718096; font-weight: bold; }

        /* Status Colors */
        .color-green { color: #047857; }
        .color-red { color: #e53e3e; }
        .color-orange { color: #d69e2e; }
        .bg-green { background-color: #ecfdf5; }
        .bg-red { background-color: #fef2f2; }
        .bg-orange { background-color: #fffbeb; }

        /* Detailed Records Table */
        .records-table {
            width: 100%;
            border-collapse: collapse;
        }
        .records-table th {
            background-color: #edf2f7;
            color: #4a5568;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #cbd5e0;
            font-size: 9px;
            text-transform: uppercase;
        }
        .records-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }

        /* Status Badges */
        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
            display: inline-block;
        }
        .badge-present { background-color: #d1fae5; color: #065f46; }
        .badge-absent { background-color: #fee2e2; color: #991b1b; }
        .badge-late { background-color: #fef3c7; color: #92400e; }

        .weekend-row { background-color: #f7fafc; color: #a0aec0; }

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
            <td width="60%">
                <div class="school-name">{{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}</div>
                <div class="school-info">
                    {{ $schoolSettings->school_address ?? 'Address Line 1' }}<br>
                    {{ $schoolSettings->phone ?? '' }}
                </div>
            </td>
            <td width="40%" align="right" valign="bottom">
                <div class="report-title">Monthly Attendance Report</div>
                <div style="font-size: 11px; color: #4a5568; margin-top: 5px;">
                    Period: <strong>{{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="profile-box">
        <table class="profile-table">
            <tr>
                <td class="label">STUDENT NAME:</td>
                <td><strong>{{ $selectedStudent->name }}</strong></td>
                <td class="label">ADMISSION NO:</td>
                <td>{{ $selectedStudent->admission_no }}</td>
            </tr>
            <tr>
                <td class="label">CLASS:</td>
                <td>{{ $selectedStudent->section->grade->name }} - {{ $selectedStudent->section->name }}</td>
                <td class="label">GENERATED ON:</td>
                <td>{{ now()->format('Y-m-d') }}</td>
            </tr>
        </table>
    </div>

    <table class="summary-table">
        <tr>
            <td class="bg-green">
                <span class="stat-value color-green">{{ $summary['present'] }}</span>
                <span class="stat-label">Days Present</span>
            </td>
            <td class="bg-orange">
                <span class="stat-value color-orange">{{ $summary['late'] }}</span>
                <span class="stat-label">Days Late</span>
            </td>
            <td class="bg-red">
                <span class="stat-value color-red">{{ $summary['absent'] }}</span>
                <span class="stat-label">Days Absent</span>
            </td>
            <td>
                <span class="stat-value" style="color: #2d3748;">{{ $summary['percentage'] }}%</span>
                <span class="stat-label">Attendance Rate</span>
            </td>
        </tr>
    </table>

    <table class="records-table">
        <thead>
            <tr>
                <th width="20%">Date</th>
                <th width="20%">Day</th>
                <th width="20%">Status</th>
                <th width="40%">Remarks</th>
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

                <tr class="{{ $isWeekend ? 'weekend-row' : '' }}">
                    <td>{{ $dateObj->format('Y-m-d') }}</td>
                    <td>{{ $dateObj->format('l') }}</td>
                    <td>
                        @if ($record)
                            @if ($record->status == 'present')
                                <span class="status-badge badge-present">PRESENT</span>
                            @elseif ($record->status == 'absent')
                                <span class="status-badge badge-absent">ABSENT</span>
                            @elseif ($record->status == 'late')
                                <span class="status-badge badge-late">LATE</span>
                            @endif
                        @else
                            @if ($isWeekend)
                                <span style="font-size: 9px;">-</span>
                            @else
                                <span style="color: #a0aec0; font-style: italic; font-size: 9px;">Not Marked</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($isWeekend)
                            <span style="font-size: 8px;">Weekend</span>
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        Confidential Student Report | System Generated
    </div>

</body>
</html>
