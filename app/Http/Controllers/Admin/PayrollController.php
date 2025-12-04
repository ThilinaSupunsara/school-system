<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\SchoolSetting;
use App\Models\Staff;
use Barryvdh\DomPDF\Facade\Pdf;
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
        // 1. Query එක පටන් ගන්නවා
        $query = Payroll::with('staff.user');

        // --- Filter Logic ---

        // Month Filter
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Year Filter
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Status Filter (Generated / Paid)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search Staff (Name)
        if ($request->filled('search')) {
            $search = $request->search;
            // Payroll -> Staff -> User හරහා නම check කරනවා
            $query->whereHas('staff.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // 2. Data ගන්නවා (Pagination 10 + Filters රැකගැනීම)
        $payrolls = $query->latest()
                          ->paginate(10)
                          ->appends($request->all());

        // View එකට යවනවා (සෙවුම් ප්‍රතිඵල පෙන්වන්න request එකත් යවන්න පුළුවන්,
        // නමුත් paginate()->appends() මගින් එය handle වෙනවා)
        return view('admin.payroll.index', compact('payrolls'));
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

    public function exportPdf(Request $request)
    {
        // 1. Query එක පටන් ගන්නවා (index method එකේ logic එකමයි)
        $query = Payroll::with('staff.user');

        // --- Filter Logic ---
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('staff.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // 2. Data ගන්නවා (ඔක්කොම records)
        $payrolls = $query->latest()->get();

        // 3. Totals ගණනය කිරීම (Summary සඳහා)
        $totalNetSalary = $payrolls->sum('net_salary');
        $totalBasic = $payrolls->sum('basic_salary');

        // 4. PDF Load කිරීම
        $schoolSettings = \App\Models\SchoolSetting::first();

        $pdf = Pdf::loadView('admin.payroll.pdf_list', compact(
            'payrolls', 'totalNetSalary', 'totalBasic', 'schoolSettings'
        ));

        // 5. Download
        return $pdf->download('Payroll-Report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('staff.user', 'payments');
        $balance = $payroll->net_salary - $payroll->paid_amount;

        return view('admin.payroll.show', compact('payroll', 'balance'));
    }
    public function storePayment(Request $request, Payroll $payroll)
    {
        $balance = $payroll->net_salary - $payroll->paid_amount;

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $balance,
            'payment_date' => 'required|date',
            'method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Payment Record
            $payroll->payments()->create([
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'method' => $request->method,
            ]);

            // 2. Update Payroll Status & Paid Amount
            $newPaidAmount = $payroll->paid_amount + $request->amount;

            $status = 'generated'; // default (pending)
            if ($newPaidAmount >= $payroll->net_salary) {
                $status = 'paid';
            } elseif ($newPaidAmount > 0) {
                $status = 'partial';
            }

            $payroll->update([
                'paid_amount' => $newPaidAmount,
                'status' => $status,
            ]);

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function printPayslip(Payroll $payroll)
    {
        $payroll->load('staff.user', 'payments');
        $schoolSettings =SchoolSetting::first();

        return view('admin.payroll.print_payslip', compact('payroll', 'schoolSettings'));
    }
}
