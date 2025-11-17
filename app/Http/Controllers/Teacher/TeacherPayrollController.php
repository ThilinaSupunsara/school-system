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
    public function show(Payroll $payroll)
    {
        // 1. Logged-in user ගේ Staff ID එක ගන්න
        $staffId = Auth::user()->staff->id;

        // 2. Security Check:
        // මේ බලන්න හදන payslip ($payroll) එකේ staff_id එක
        // login වෙලා ඉන්න user ගේ staff_id එකට සමානද?
        if ($payroll->staff_id != $staffId) {
            // සමාන නැත්නම්, "තහනම්" (Forbidden)
            abort(403, 'THIS IS NOT YOUR PAYSLIP.');
        }

        // 3. සමාන නම්, Admin ලා බලන payslip view එකම reuse කරමු
        return view('admin.payroll.payslip', compact('payroll'));
    }
}
