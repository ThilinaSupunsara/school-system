<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherPayrollController extends Controller
{
    public function index()
    {
        // 1. Logged-in user ගේ Staff ID එක ගන්න
        $staffId = Auth::user()->staff->id;

        // 2. ඒ Staff ID එකට අදාළ payrolls විතරක් ගන්න
        $payrolls = Payroll::where('staff_id', $staffId)
                            ->latest()
                            ->paginate(15);

        // 3. අලුත් view එකකට යවන්න
        return view('teacher.payroll.index', compact('payrolls'));
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
