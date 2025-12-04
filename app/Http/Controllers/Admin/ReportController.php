<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\SchoolSetting;
use App\Models\Section;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function outstandingFees(Request $request)
    {
        // 1. Filter Dropdowns වලට data ටික අරගන්නවා
        $grades = Grade::all();
        $sections = Section::all();

        // 2. Invoices ටික query කරන්න පටන් ගන්නවා
        // Eager load: Student, Student's Section, and Section's Grade
        $query = Invoice::with('student.section.grade')
                        ->whereIn('status', ['pending', 'partial'])
                        ->orderBy('due_date', 'asc'); // පරණම ඒවා උඩට

        // 3. Filter Logic
        // Grade එකක් select කරලා තියෙනවද?
        if ($request->filled('grade_id')) {
            $gradeId = $request->grade_id;
            $query->whereHas('student.section', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        }

        // Section එකක් select කරලා තියෙනවද?
        if ($request->filled('section_id')) {
            $sectionId = $request->section_id;
            $query->whereHas('student', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            });
        }

        // 4. Filter කරපු Invoices ටික ගන්නවා
        $invoices = $query->get();

        // 5. මුළු ඉතුරු ගාණ (Balance Due) حساب කරනවා
        $totalOutstanding = $invoices->sum(function($invoice) {
            return $invoice->total_amount - $invoice->paid_amount;
        });

        // 6. View එකට data ටික pass කරනවා
        return view('admin.reports.outstanding_fees', compact(
            'grades',
            'sections',
            'invoices',
            'totalOutstanding'
        ));
    }

    public function salarySheet(Request $request)
    {
        // 1. Validate the request (nullable, so it's okay if they don't exist)
        $validated = $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:2050',
        ]);

        // 2. Select the month/year.
        // User තෝරලා එව්වොත් ඒක ගන්නවා.
        // නැත්නම්, වත්මන් මාසය/අවුරුද්ද default විදිහට ගන්නවා.
        $selectedMonth = (int)($validated['month'] ?? now()->month);
        $selectedYear = (int)($validated['year'] ?? now()->year);

        // 3. හැමවිටම query එක run කරනවා (Default මාසෙට හරි, තෝරපු මාසෙට හරි)
        $payrolls = Payroll::with('staff.user')
                            ->where('month', $selectedMonth)
                            ->where('year', $selectedYear)
                            ->get();

        // 4. Totals حساب කරනවා
        $totals = [
            'basic' => $payrolls->sum('basic_salary'),
            'allowances' => $payrolls->sum('total_allowances'),
            'deductions' => $payrolls->sum('total_deductions'),
            'net' => $payrolls->sum('net_salary'),
        ];

        // 5. View එකට data ටික pass කරනවා
        return view('admin.reports.salary_sheet', compact(
            'payrolls',
            'totals',
            'selectedMonth',
            'selectedYear'
        ));
    }

    public function dailyAttendance(Request $request)
    {
        // 1. දිනය තෝරාගැනීම (User එව්වේ නැත්නම් අද දවස)
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // 2. Sections ටික ගන්නවා (Eager Loading එක්ක)
        //    - with('grade'): Grade නම ගන්න.
        //    - withCount('students'): පන්තියේ මුළු ළමයි ගණන ගන්න.
        //    - with('attendances'): අදාළ දවසට mark කරපු attendance විතරක් ගන්න.
        $sections = Section::with(['grade', 'attendances' => function($query) use ($selectedDate) {
            $query->whereDate('attendance_date', $selectedDate);
        }])->withCount('students')->get();

        // 3. Report Data එක සකස් කිරීම
        $reportData = $sections->map(function ($section) {
            $totalStudents = $section->students_count;

            // අදාළ දවසේ records වලින් ගණන් හදනවා
            $present = $section->attendances->where('status', 'present')->count();
            $absent = $section->attendances->where('status', 'absent')->count();
            $late = $section->attendances->where('status', 'late')->count();

            // Attendance mark කරලා නැති ගණන (Not Marked)
            $notMarked = $totalStudents - ($present + $absent + $late);

            return (object) [
                'grade' => $section->grade->name,
                'section' => $section->name,
                'total' => $totalStudents,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'not_marked' => $notMarked,
                // Percentage (Present + Late)
                'percentage' => $totalStudents > 0 ? round((($present + $late) / $totalStudents) * 100, 1) : 0,
            ];
        });

        return view('admin.reports.daily_attendance', compact('reportData', 'selectedDate'));
    }

    public function studentMonthlyAttendance(Request $request)
    {
        // 1. Dropdowns වලට Data
        $grades = Grade::all();
        $sections = Section::all();

        // 2. Students Query (Filter Logic)
        $studentsQuery = Student::with('section.grade');

        // Grade එකක් තෝරලා නම් filter කරන්න
        if ($request->filled('grade_id')) {
            $studentsQuery->whereHas('section', function($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }
        // Section එකක් තෝරලා නම් filter කරන්න
        if ($request->filled('section_id')) {
            $studentsQuery->where('section_id', $request->section_id);
        }

        // නම අනුව අකුරු පිළිවෙලට (Alphabetical) ගන්න
        $students = $studentsQuery->orderBy('name')->get();

        // 3. Report Variables
        $attendanceRecords = collect();
        $summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'total_days' => 0, 'percentage' => 0];
        $selectedStudent = null;
        $daysInMonth = 0;

        // 4. Report Generation (Form එක submit කරලා නම්)
        if ($request->filled('student_id') && $request->filled('month') && $request->filled('year')) {

            $selectedStudent = Student::find($request->student_id);

            if ($selectedStudent) {
                $month = $request->month;
                $year = $request->year;
                $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

                $records = Attendance::where('student_id', $request->student_id)
                                     ->whereYear('attendance_date', $year)
                                     ->whereMonth('attendance_date', $month)
                                     ->get()
                                     ->keyBy('attendance_date');

                $attendanceRecords = $records;

                // Summary
                $summary['present'] = $records->where('status', 'present')->count();
                $summary['absent'] = $records->where('status', 'absent')->count();
                $summary['late'] = $records->where('status', 'late')->count();
                $summary['total_days'] = $summary['present'] + $summary['absent'] + $summary['late'];

                if ($summary['total_days'] > 0) {
                    $summary['percentage'] = round((($summary['present'] + $summary['late']) / $summary['total_days']) * 100, 1);
                }
            }
        }

        return view('admin.reports.student_monthly_attendance', compact(
            'grades', 'sections', 'students',
            'attendanceRecords', 'summary', 'selectedStudent', 'daysInMonth'
        ));
    }

    /**
     * Show the monthly attendance report for a whole class (section).
     */
    public function classAttendance(Request $request)
    {
        $grades = Grade::all(); // Grade Filter එකට
        $sections = Section::all(); // All sections for filtering logic in JS

        $students = collect();
        $attendanceMatrix = [];
        $daysInMonth = 0;
        $selectedSection = null;

        if ($request->filled('section_id') && $request->filled('month') && $request->filled('year')) {
            // (කලින් Logic එකමයි)
            $month = $request->month;
            $year = $request->year;
            $sectionId = $request->section_id;

            $selectedSection = Section::with('grade')->find($sectionId);
            $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

            $students = Student::where('section_id', $sectionId)->orderBy('name')->get();

            $attendances = Attendance::whereIn('student_id', $students->pluck('id'))
                                     ->whereYear('attendance_date', $year)
                                     ->whereMonth('attendance_date', $month)
                                     ->get();

            foreach ($attendances as $att) {
                $attendanceMatrix[$att->student_id][$att->attendance_date] = $att->status;
            }
        }

        return view('admin.reports.class_attendance', compact(
            'grades', 'sections', 'students', 'attendanceMatrix',
            'daysInMonth', 'selectedSection'
        ));
    }

    public function exportOutstandingFeesPdf(Request $request)
    {
        // 1. --- (FILTERING LOGIC - කලින් method එකේ තිබ්බ ටිකමයි) ---
        $query = Invoice::with('student.section.grade')
                        ->whereIn('status', ['pending', 'partial'])
                        ->orderBy('due_date', 'asc');

        if ($request->filled('grade_id')) {
            $gradeId = $request->grade_id;
            $query->whereHas('student.section', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        }

        if ($request->filled('section_id')) {
            $sectionId = $request->section_id;
            $query->whereHas('student', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            });
        }

        $invoices = $query->get();
        $totalOutstanding = $invoices->sum(function($invoice) {
            return $invoice->total_amount - $invoice->paid_amount;
        });
        // ---------------------------------------------------------

        // 2. School Settings ගන්න (Header එකට)
        $schoolSettings = SchoolSetting::first();

        // 3. PDF එක Load කරනවා (අපි අලුත් clean view එකක් හදමු 'admin.reports.pdf.outstanding')
        $pdf = Pdf::loadView('admin.reports.pdf.outstanding', compact('invoices', 'totalOutstanding', 'schoolSettings'));

        $todayDate = now()->format('Y-m-d');

        // නමට දිනය එකතු කරනවා
        return $pdf->download('outstanding-fees-report-' . $todayDate . '.pdf');
    }
    public function exportSalarySheetPdf(Request $request)
    {
        // 1. Data ලබාගැනීම (salarySheet method එකේ logic එකමයි)
        $validated = $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:2050',
        ]);

        $selectedMonth = (int)($validated['month'] ?? now()->month);
        $selectedYear = (int)($validated['year'] ?? now()->year);

        $payrolls = Payroll::with('staff.user')
                            ->where('month', $selectedMonth)
                            ->where('year', $selectedYear)
                            ->get();

        $totals = [
            'basic' => $payrolls->sum('basic_salary'),
            'allowances' => $payrolls->sum('total_allowances'),
            'deductions' => $payrolls->sum('total_deductions'),
            'net' => $payrolls->sum('net_salary'),
        ];

        // 2. School Settings ගන්න
        $schoolSettings = SchoolSetting::first();

        // 3. PDF එක Load කරනවා
        $pdf = Pdf::loadView('admin.reports.pdf.salary_sheet', compact(
            'payrolls',
            'totals',
            'selectedMonth',
            'selectedYear',
            'schoolSettings'
        ));

        // 4. Filename එක හදනවා (Salary-Sheet-November-2025.pdf)
        $monthName = Carbon::create()->month($selectedMonth)->format('F');
        $fileName = 'Salary-Sheet-' . $monthName . '-' . $selectedYear . '.pdf';

        return $pdf->download($fileName);
    }

    public function exportDailyAttendancePdf(Request $request)
    {
        // 1. දිනය තෝරාගැනීම
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // 2. Data ලබාගැනීම (Sections සහ Attendance Counts)
        $sections = Section::with(['grade', 'attendances' => function($query) use ($selectedDate) {
            $query->whereDate('attendance_date', $selectedDate);
        }])->withCount('students')->get();

        // 3. Report Data සකස් කිරීම
        $reportData = $sections->map(function ($section) {
            $totalStudents = $section->students_count;
            $present = $section->attendances->where('status', 'present')->count();
            $absent = $section->attendances->where('status', 'absent')->count();
            $late = $section->attendances->where('status', 'late')->count();
            $notMarked = $totalStudents - ($present + $absent + $late);

            return (object) [
                'grade' => $section->grade->name,
                'section' => $section->name,
                'total' => $totalStudents,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'not_marked' => $notMarked,
                'percentage' => $totalStudents > 0 ? round((($present + $late) / $totalStudents) * 100, 1) : 0,
            ];
        });

        // 4. School Settings සහ PDF Load කිරීම
        $schoolSettings = SchoolSetting::first();

        $pdf = Pdf::loadView('admin.reports.pdf.daily_attendance', compact(
            'reportData',
            'selectedDate',
            'schoolSettings'
        ));

        // 5. Download (Filename: Daily-Attendance-2025-11-27.pdf)
        return $pdf->download('Daily-Attendance-' . $selectedDate . '.pdf');
    }

    public function exportClassAttendancePdf(Request $request)
    {
        // 1. Data ලබාගැනීම (ඉහත Logic එකමයි)
        $month = $request->month;
        $year = $request->year;
        $sectionId = $request->section_id;

        $selectedSection = Section::with('grade')->find($sectionId);
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

        $students = Student::where('section_id', $sectionId)->orderBy('name')->get();

        $attendances = Attendance::whereIn('student_id', $students->pluck('id'))
                                    ->whereYear('attendance_date', $year)
                                    ->whereMonth('attendance_date', $month)
                                    ->get();

        $attendanceMatrix = [];
        foreach ($attendances as $att) {
            $attendanceMatrix[$att->student_id][$att->attendance_date] = $att->status;
        }

        $schoolSettings = SchoolSetting::first();

       // PDF එක Load කිරීම
        $pdf = Pdf::loadView('admin.reports.pdf.class_attendance', compact(
            'students', 'attendanceMatrix', 'daysInMonth', 'selectedSection',
            'month', 'year', 'schoolSettings'
        ));

        $pdf->setPaper('a4', 'landscape');

        // ===== ගොනුවේ නම (Filename) සැකසීම =====
        // Grade නමේ තියෙන හිස්තැන් (Spaces) ඉවත් කරමු (උදා: "Grade 6" -> "Grade-6")
        $gradeName = str_replace(' ', '-', $selectedSection->grade->name);
        $sectionName = $selectedSection->name;

        // උදා: "Attendance_Grade-6-A_November-2025.pdf"
        $fileName = 'Attendance_' . $gradeName . '-' . $sectionName . '_' . $month . '-' . $year . '.pdf';

        return $pdf->download($fileName);
    }

    public function exportStudentAttendancePdf(Request $request)
    {
        // 1. Data Validate කිරීම
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2050',
        ]);

        $studentId = $request->student_id;
        $month = $request->month;
        $year = $request->year;

        // 2. Data ලබාගැනීම
        $selectedStudent = Student::with('section.grade')->find($studentId);
        $daysInMonth = \Carbon\Carbon::createFromDate($year, $month)->daysInMonth;

        $records = Attendance::where('student_id', $studentId)
                             ->whereYear('attendance_date', $year)
                             ->whereMonth('attendance_date', $month)
                             ->get()
                             ->keyBy('attendance_date');

        $attendanceRecords = $records;

        // 3. Summary ගණනය කිරීම
        $summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'total_days' => 0, 'percentage' => 0];
        $summary['present'] = $records->where('status', 'present')->count();
        $summary['absent'] = $records->where('status', 'absent')->count();
        $summary['late'] = $records->where('status', 'late')->count();
        $summary['total_days'] = $summary['present'] + $summary['absent'] + $summary['late'];

        if ($summary['total_days'] > 0) {
            $summary['percentage'] = round((($summary['present'] + $summary['late']) / $summary['total_days']) * 100, 1);
        }

        // 4. School Settings සහ PDF Load කිරීම
        $schoolSettings = SchoolSetting::first();

        $pdf = Pdf::loadView('admin.reports.pdf.student_monthly_attendance', compact(
            'attendanceRecords', 'summary', 'selectedStudent', 'daysInMonth',
            'month', 'year', 'schoolSettings'
        ));

        // 5. Filename එක සෑදීම (උදා: Report_Kamal_November_2025.pdf)
        $studentName = str_replace(' ', '_', $selectedStudent->name);
        $monthName = Carbon::createFromDate($year, $month)->format('F');
        $fileName = 'Report_' . $studentName . '_' . $monthName . '_' . $year . '.pdf';

        return $pdf->download($fileName);
    }
}
