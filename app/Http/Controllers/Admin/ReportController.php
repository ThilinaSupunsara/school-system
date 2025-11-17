<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\Section;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function outstandingFees(Request $request)
    {
        // 1. Filter Dropdowns වලට data ටික අරගන්නවා
        $grades = Grade::all();
        $sections = Section::all();

        // 2. Invoices ටික query කරන්න පටන් ගන්නවා
        // Eager load: Student, Student's Section, and Section's Grade
        $query = Invoice::with('student.section.grade')
                        ->whereIn('status', ['pending', 'partial'])
                        ->orderBy('due_date', 'asc'); // පරණම ඒවා උඩට

        // 3. Filter Logic
        // Grade එකක් select කරලා තියෙනවද?
        if ($request->filled('grade_id')) {
            $gradeId = $request->grade_id;
            $query->whereHas('student.section', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        }

        // Section එකක් select කරලා තියෙනවද?
        if ($request->filled('section_id')) {
            $sectionId = $request->section_id;
            $query->whereHas('student', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            });
        }

        // 4. Filter කරපු Invoices ටික ගන්නවා
        $invoices = $query->get();

        // 5. මුළු ඉතුරු ගාණ (Balance Due) حساب කරනවා
        $totalOutstanding = $invoices->sum(function($invoice) {
            return $invoice->total_amount - $invoice->paid_amount;
        });

        // 6. View එකට data ටික pass කරනවා
        return view('admin.reports.outstanding_fees', compact(
            'grades',
            'sections',
            'invoices',
            'totalOutstanding'
        ));
    }

    public function salarySheet(Request $request)
    {
        // 1. Validate the request (nullable, so it's okay if they don't exist)
        $validated = $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:2050',
        ]);

        // 2. Select the month/year.
        // User තෝරලා එව්වොත් ඒක ගන්නවා.
        // නැත්නම්, වත්මන් මාසය/අවුරුද්ද default විදිහට ගන්නවා.
        $selectedMonth = (int)($validated['month'] ?? now()->month);
        $selectedYear = (int)($validated['year'] ?? now()->year);

        // 3. හැමවිටම query එක run කරනවා (Default මාසෙට හරි, තෝරපු මාසෙට හරි)
        $payrolls = Payroll::with('staff.user')
                            ->where('month', $selectedMonth)
                            ->where('year', $selectedYear)
                            ->get();

        // 4. Totals حساب කරනවා
        $totals = [
            'basic' => $payrolls->sum('basic_salary'),
            'allowances' => $payrolls->sum('total_allowances'),
            'deductions' => $payrolls->sum('total_deductions'),
            'net' => $payrolls->sum('net_salary'),
        ];

        // 5. View එකට data ටික pass කරනවා
        return view('admin.reports.salary_sheet', compact(
            'payrolls',
            'totals',
            'selectedMonth',
            'selectedYear'
        ));
    }
}
