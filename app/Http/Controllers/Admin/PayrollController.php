<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function showProcessForm()
{
    // View එකට default month/year යවමු
    $currentMonth = now()->month; // e.g., 11
    $currentYear = now()->year;  // e.g., 2025

    return view('admin.payroll.process', compact('currentMonth', 'currentYear'));
}

/**
 * Process and store payroll for all staff for a given month/year.
 */
    public function processPayroll(Request $request)
    {
        // 1. Validation
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2050',
        ]);

        $month = $request->month;
        $year = $request->year;

        // 2. Check if payroll already processed for this month
        $existingPayroll = Payroll::where('month', $month)->where('year', $year)->first();
        if ($existingPayroll) {
            return back()->with('error', 'Payroll has already been processed for this month and year.');
        }

        // 3. Get all staff with their allowances and deductions
        $staffMembers = Staff::with('allowances', 'deductions')->get();

        if ($staffMembers->isEmpty()) {
            return back()->with('error', 'No staff members found to process payroll.');
        }

        // 4. Database Transaction
        DB::beginTransaction();
        try {
            $processedCount = 0;
            foreach ($staffMembers as $staff) {
                // 5. Calculate totals
                $basicSalary = $staff->basic_salary ?? 0;
                $totalAllowances = $staff->allowances->sum('amount');
                $totalDeductions = $staff->deductions->sum('amount');
                $netSalary = ($basicSalary + $totalAllowances) - $totalDeductions;

                // 6. Create Payroll Record
                Payroll::create([
                    'staff_id' => $staff->id,
                    'year' => $year,
                    'month' => $month,
                    'basic_salary' => $basicSalary,
                    'total_allowances' => $totalAllowances,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'status' => 'generated',
                ]);
                $processedCount++;
            }

            // 7. Commit Transaction
            DB::commit();

            return redirect()->route('finance.payroll.index') // Payroll list එකට යවමු
                            ->with('success', "$processedCount payroll records generated successfully for $month/$year.");

        } catch (\Exception $e) {
            // 8. Rollback Transaction
            DB::rollBack();
            return back()->with('error', 'An error occurred during payroll processing. No records were created. Error: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        // Eager load staff and their user details
        // 'latest()' දාලා අලුත්ම payroll records උඩින්ම පෙන්නමු
        $payrolls = Payroll::with('staff.user')
                            ->latest()
                            ->paginate(20);

        return view('admin.payroll.index', compact('payrolls'));
    }

    /**
     * Display the payslip for a specific payroll record.
     */
    public function showPayslip(Payroll $payroll)
    {
        // Eager load the relationships
        $payroll->load('staff.user');

        return view('admin.payroll.payslip', compact('payroll'));
    }
    public function toggleStatus(Payroll $payroll)
    {
        // Status එක toggle (මාරු) කරනවා
        $newStatus = $payroll->status === 'generated' ? 'paid' : 'generated';

        $payroll->update([
            'status' => $newStatus,
        ]);

        // ආපහු list එකටම redirect කරනවා
        return redirect()->route('finance.payroll.index')
                         ->with('success', 'Payroll status updated to ' . $newStatus . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        // Payroll record එක delete කිරීම
        $payroll->delete();

        return redirect()->route('finance.payroll.index')
                         ->with('success', 'Payroll record deleted successfully.');
    }
}
