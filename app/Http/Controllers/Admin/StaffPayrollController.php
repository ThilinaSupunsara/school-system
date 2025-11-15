<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffPayrollController extends Controller
{
    public function edit(Staff $staff)
    {
        // Eager load the relationships
        $staff->load('user', 'allowances', 'deductions');

        // අලුත් view එකකට යවමු
        return view('admin.staff.payroll', compact('staff'));
    }
}
