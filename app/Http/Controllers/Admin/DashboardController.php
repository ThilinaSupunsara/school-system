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

        $studentCount = Student::count();


        $staffCount = Staff::count();


        $monthlyIncome = Payment::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount');


        $monthlyExpense = Payroll::where('year', now()->year)
                                ->where('month', now()->month)
                                ->where('status', 'paid')
                                ->sum('net_salary');

    
        return view('admin.dashboard', compact(
            'studentCount',
            'staffCount',
            'monthlyIncome',
            'monthlyExpense'
        ));
    }
}
