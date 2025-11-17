<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function showClassList()
    {
        // 1. Logged-in teacher භාරව ඉන්න පන්ති ටික ගන්නවා
        $assignedSections = Auth::user()->staff->classSections; // (අපි fix කරපු hasMany relationship එක)

        return view('teacher.attendance.select_class', compact('assignedSections'));
    }

    /**
     * Show the attendance marking form for a specific section.
     */
    public function showMarkForm(Section $section)
    {
        // 1. Security Check
        $this->authorizeTeacherForSection($section);

        // 2. ළමයි ටික ගන්නවා
        $students = $section->students()->get();
        $studentIds = $students->pluck('id');

        // 3. දවස් 5 (සති අන්ත (Weekends) නැතුව)
        $today = Carbon::today();
        $lastFiveSchoolDays = [];
        $date = $today->clone()->subDay(); // ඊයේ ඉඳන් පටන් ගමු

        while (count($lastFiveSchoolDays) < 5) {
            if (!$date->isWeekend()) {
                // List එකේ මුලට දානවා (පරණම දවස මුලින්)
                array_unshift($lastFiveSchoolDays, $date->clone());
            }
            $date->subDay();
        }
        // $lastFiveSchoolDays = [Day-5, Day-4, Day-3, Day-2, Day-1]

        // 4. අද දවසේ Attendance (Mark කරලද බලන්න)
        $attendanceData = Attendance::where('attendance_date', $today)
                                    ->whereIn('student_id', $studentIds)
                                    ->pluck('status', 'student_id');

        // 5. මාසික Absent Count (History)
        $monthlyAbsentCounts = Attendance::where('status', 'absent')
            ->whereIn('student_id', $studentIds)
            ->whereYear('attendance_date', $today->year)
            ->whereMonth('attendance_date', $today->month)
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as absent_count'))
            ->pluck('absent_count', 'student_id');

        // 6. දවස් 5ක History Data (අලුත් Query එක)
        $startDate = $lastFiveSchoolDays[0]; // පරණම දවස
        $endDate = $lastFiveSchoolDays[4];   // ඊයේ

        $pastAttendanceRaw = Attendance::whereIn('student_id', $studentIds)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get(['student_id', 'attendance_date', 'status']);

        // 7. Data ටික View එකට ලේසි විදිහකට හදනවා
        //    $pastAttendance[student_id][date_string] = 'status'
        $pastAttendance = [];
        foreach ($pastAttendanceRaw as $att) {
            $dateString = Carbon::parse($att->attendance_date)->format('Y-m-d');
            $pastAttendance[$att->student_id][$dateString] = $att->status;
        }

        // 8. View එකට හැමදේම යවනවා
        return view('teacher.attendance.mark', compact(
            'section',
            'students',
            'attendanceData',
            'monthlyAbsentCounts',
            'lastFiveSchoolDays', // <-- අලුත්
            'pastAttendance',     // <-- අලුත්
            'today'
        ));
    }

    /**
     * Store the attendance data for a specific section.
     */
    public function storeAttendance(Request $request, Section $section)
    {
        // 1. Security Check
        $this->authorizeTeacherForSection($section);

        $request->validate([
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late',
        ]);

        $attendanceDate = $request->attendance_date;

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $studentId => $status) {
                // (Student ID එක, මේ section එකේ ළමයෙක්ගෙද කියලත් බලන්න ඕන - අමතර ආරක්ෂාවට)
                $studentExists = $section->students()->where('id', $studentId)->exists();

                if ($studentExists) {
                    Attendance::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'attendance_date' => $attendanceDate,
                        ],
                        [
                            'status' => $status,
                        ]
                    );
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred. Attendance was not saved. ' . $e->getMessage());
        }

        return back()->with('success', 'Attendance for ' . $attendanceDate . ' saved successfully!');
    }

    /**
     * Helper function for security check.
     */
    private function authorizeTeacherForSection(Section $section)
    {
        // මේ section එකේ class_teacher_id එක,
        // login වුණ user ගේ staff->id එකට සමානද?
        if ($section->class_teacher_id !== Auth::user()->staff->id) {
            abort(403, 'UNAUTHORIZED. THIS IS NOT YOUR CLASS.');
        }
    }
}
