<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherPayrollController extends Controller
{
    public function index(Request $request)
    {
        // 1. Log wela inna Teacher ge Staff ID eka gannawa
        $staff = Auth::user()->staff;

        if (!$staff) {
            abort(403, 'Staff profile not found.');
        }

        // 2. Query eka patan gannawa (e teacher ge payrolls witharak)
        $query = Payroll::where('staff_id', $staff->id)->latest();

        // --- FILTERS ---
        
        // Month Filter
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Year Filter
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // 3. Pagination (10 per page) + Filter parameters thiyaganna appends() danawa
        $payrolls = $query->paginate(10)->appends($request->all());

        // Dropdown walata Awurudu (Years) list eka gannawa
        $years = Payroll::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('teacher.payroll.index', compact('payrolls', 'years'));
    }

    /**
     * Display a specific payslip, ONLY IF it belongs to the logged-in teacher.
     */
    /**
     * Display a specific payslip.
     */
    public function show(Payroll $payroll)
    {
        // 1. Logged-in user ගේ Staff ID එක ගන්න
        $staffId = Auth::user()->staff->id;

        // 2. Security Check (මේක තමන්ගේ පඩිපතද?)
        if ($payroll->staff_id != $staffId) {
            abort(403, 'THIS IS NOT YOUR PAYSLIP.');
        }

        // 3. School Settings ගන්න (Header එකට ඕන නිසා)
        $schoolSettings = \App\Models\SchoolSetting::first();

        // 4. අලුත් Print View එකට යවනවා
        // (අපි Admin ට හදපු ලස්සන Print view එකම පාවිච්චි කරමු)
        return view('admin.payroll.print_payslip', compact('payroll', 'schoolSettings'));
    }
}
