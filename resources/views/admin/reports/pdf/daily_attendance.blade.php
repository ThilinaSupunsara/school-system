<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daily Attendance Report - {{ $selectedDate }}</title>
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
            border-bottom: 2px solid #2d3748; /* Dark gray line */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a202c;
            margin: 0;
        }
        .school-info {
            font-size: 10px;
            color: #718096;
            margin: 2px 0 0 0;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            text-transform: uppercase;
            text-align: right;
            margin: 0;
        }
        .report-date {
            font-size: 12px;
            color: #4a5568;
            text-align: right;
            margin-top: 5px;
            font-weight: bold;
        }

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
            padding: 8px 6px;
            border-bottom: 1px solid #cbd5e0;
            text-align: center;
        }

        .data-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
        }

        /* Row Striping */
        .data-table tr:nth-child(even) { background-color: #fcfcfc; }

        /* Alignments */
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }

        /* Status Colors */
        .status-present { color: #047857; font-weight: bold; } /* Green */
        .status-absent { color: #e53e3e; font-weight: bold; } /* Red */
        .status-late { color: #d69e2e; font-weight: bold; } /* Orange */
        .status-gray { color: #a0aec0; } /* Gray for zero/empty */

        /* Totals Row */
        .grand-total-row td {
            background-color: #edf2f7;
            border-top: 2px solid #4a5568;
            font-weight: bold;
            font-size: 11px;
            padding: 10px 6px;
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
            <td style="vertical-align: top;">
                <h1 class="school-name">{{ $schoolSettings->school_name ?? 'SCHOOL NAME' }}</h1>
                <p class="school-info">
                    {{ $schoolSettings->school_address ?? 'Address Line 1' }}<br>
                    {{ $schoolSettings->phone ?? '' }}
                </p>
            </td>
            <td style="vertical-align: bottom;">
                <h2 class="report-title">Daily Attendance Summary</h2>
                <div class="report-date">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, F d, Y') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-left" width="25%">Class</th>
                <th width="12%">Total</th>
                <th width="12%">Present</th>
                <th width="12%">Late</th>
                <th width="12%">Absent</th>
                <th width="12%">Pending</th>
                <th width="15%" class="text-right">Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
                <tr>
                    <td class="text-left" style="font-weight: bold; color: #2d3748;">
                        {{ $row->grade }} - {{ $row->section }}
                    </td>
                    <td>{{ $row->total }}</td>
                    <td class="status-present">{{ $row->present }}</td>
                    <td class="status-late">{{ $row->late }}</td>
                    <td class="status-absent">{{ $row->absent }}</td>
                    <td>
                        @if($row->not_marked > 0)
                            <span style="color: #e53e3e; font-weight: bold;">{{ $row->not_marked }}</span>
                        @else
                            <span class="status-gray">-</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ $row->percentage }}%
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="grand-total-row">
                <td class="text-left">GRAND TOTALS</td>
                <td>{{ $reportData->sum('total') }}</td>
                <td class="status-present">{{ $reportData->sum('present') }}</td>
                <td class="status-late">{{ $reportData->sum('late') }}</td>
                <td class="status-absent">{{ $reportData->sum('absent') }}</td>
                <td>{{ $reportData->sum('not_marked') }}</td>
                <td class="text-right">
                    @php
                        $grandTotal = $reportData->sum('total');
                        $grandPresent = $reportData->sum('present') + $reportData->sum('late');
                        $grandPct = $grandTotal > 0 ? round(($grandPresent / $grandTotal) * 100, 1) : 0;
                    @endphp
                    {{ $grandPct }}%
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated by System on {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
