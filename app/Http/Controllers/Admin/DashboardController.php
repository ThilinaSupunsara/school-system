<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
            // 1. මුළු ශිෂ්‍යයින් ගණන
        $studentCount = Student::count();

        // 2. මුළු කාර්ය මණ්ඩලය ගණන
        $staffCount = Staff::count();

        // 3. මේ මාසයේ ආදායම (ලැබුණු ගෙවීම්)
        $monthlyIncome = Payment::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount');

        // 4. මේ මාසයේ වියදම් (ගෙවූ වැටුප්)
        $monthlyExpense = Payroll::where('year', now()->year)
                                ->where('month', now()->month)
                                ->where('status', 'paid') // 'paid' status එකේ ඒවා විතරක්
                                ->sum('net_salary');

        // මේ දත්ත 4 view එකට pass කරනවා
        return view('admin.dashboard', compact(
            'studentCount',
            'staffCount',
            'monthlyIncome',
            'monthlyExpense'
        ));
    }
}
