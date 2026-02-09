<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allowance;
use App\Models\Staff;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    public function store(Request $request, Staff $staff)
    {
        // 1. Validation
        $request->validate([
            'allowance_name' => 'required|string|max:255',
            'allowance_amount' => 'required|numeric|min:0',
        ]);

        // 2. Create & Save
        $staff->allowances()->create([
            'name' => $request->allowance_name,
            'amount' => $request->allowance_amount,
        ]);

        // 3. Redirect back to the staff edit page
        return redirect()->route('finance.staff.payroll.edit', $staff->id)
                         ->with('success', 'Allowance added successfully.');
    }

    
    public function destroy(Allowance $allowance)
    {
      
        $staffId = $allowance->staff_id;

  
        $allowance->delete();

    
        return redirect()->route('finance.staff.payroll.edit', $staffId)
                         ->with('success', 'Allowance removed successfully.');
    }
}
