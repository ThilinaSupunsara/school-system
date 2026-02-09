<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Models\Staff;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    public function store(Request $request, Staff $staff)
    {
        // 1. Validation
        $request->validate([
            'deduction_name' => 'required|string|max:255',
            'deduction_amount' => 'required|numeric|min:0',
        ]);

        // 2. Create & Save
        $staff->deductions()->create([
            'name' => $request->deduction_name,
            'amount' => $request->deduction_amount,
        ]);

        // 3. Redirect back to the staff edit page
        return redirect()->route('finance.staff.payroll.edit', $staff->id)
                         ->with('success', 'Deduction added successfully.');
    }

    /**
     * Delete a specific deduction.
     */
    public function destroy(Deduction $deduction)
    {

        $staffId = $deduction->staff_id;


        $deduction->delete();

        
        return redirect()->route('finance.staff.payroll.edit', $staffId)
                         ->with('success', 'Deduction removed successfully.');
    }
}
