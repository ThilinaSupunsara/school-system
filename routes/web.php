<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffPayrollController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboardController;
use App\Http\Controllers\Admin\AllowanceController;
use App\Http\Controllers\Admin\DeductionController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherPayrollController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'accountant') {
            return redirect()->route('accountant.dashboard');
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        Auth::logout();
        return redirect('/login')->with('error', 'Your role is not defined.');

    })->middleware(['verified'])->name('dashboard');

        // Admin Dashboard
        Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::resource('staff', StaffController::class);

            Route::get('staff/{staff}/payroll', [StaffPayrollController::class, 'edit'])->name('staff.payroll.edit');
            Route::post('staff/{staff}/allowances', [AllowanceController::class, 'store'])->name('staff.allowances.store');
            Route::delete('allowances/{allowance}', [AllowanceController::class, 'destroy'])->name('allowances.destroy');

            // Routes for Staff Deductions
            Route::post('staff/{staff}/deductions', [DeductionController::class, 'store'])->name('staff.deductions.store');
            Route::delete('deductions/{deduction}', [DeductionController::class, 'destroy'])->name('deductions.destroy');


            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

            Route::get('roles', [UserRoleController::class, 'index'])->name('roles.index');
            Route::post('roles', [UserRoleController::class, 'store'])->name('roles.store');
            Route::delete('roles/{role}', [UserRoleController::class, 'destroy'])->name('roles.destroy');

            // Scholarships Management
            Route::get('scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
            Route::post('scholarships', [ScholarshipController::class, 'store'])->name('scholarships.store');
            Route::delete('scholarships/{scholarship}', [ScholarshipController::class, 'destroy'])->name('scholarships.destroy');

            // Assign to Student Routes
            Route::get('students/{student}/scholarships', [ScholarshipController::class, 'assignForm'])->name('students.scholarships.assign');
            Route::post('students/{student}/scholarships', [ScholarshipController::class, 'assignStore'])->name('students.scholarships.store');
            Route::delete('students/{student}/scholarships/{scholarship}', [ScholarshipController::class, 'assignDestroy'])->name('students.scholarships.destroy');

            Route::get('permissions-matrix', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])
                ->name('permissions.matrix');

            Route::post('permissions-matrix', [\App\Http\Controllers\Admin\RolePermissionController::class, 'update'])
                ->name('permissions.update');
        });

        // Accountant Dashboard
        Route::middleware(['role:accountant'])
        ->prefix('accountant')
        ->name('accountant.')
        ->group(function () {
            Route::get('/dashboard', [AccountantDashboardController::class, 'index'])->name('dashboard');

        });

        // Teacher Dashboard
        Route::middleware(['role:teacher'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {
            Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

            // අලුතින් හදන Payslips List Page
            Route::get('my-payslips', [TeacherPayrollController::class, 'index'])->name('payroll.index');

            // අලුතින් හදන Payslip View Page (Security Check එකක් එක්ක)
            Route::get('my-payslips/{payroll}', [TeacherPayrollController::class, 'show'])->name('payroll.show');
                // 1. පන්ති තෝරන List එක පෙන්වන route (GET)
            Route::get('attendance', [AttendanceController::class, 'showClassList'])->name('attendance.class_list');

            // 2. Attendance mark කරන form එක පෙන්වන route (GET)
            Route::get('attendance/{section}', [AttendanceController::class, 'showMarkForm'])->name('attendance.mark.form');

            // 3. Attendance save කරන route (POST)
            Route::post('attendance/{section}', [AttendanceController::class, 'storeAttendance'])->name('attendance.mark.store');

        });

        // ===== FINANCE GROUP (Admin & Accountant) =====
        Route::middleware(['auth', 'role:admin|accountant'])
            ->prefix('finance')
            ->name('finance.')
            ->group(function () {

            // Fee Categories CRUD
            Route::resource('fee-categories', FeeCategoryController::class);
            Route::resource('fee-structures', FeeStructureController::class);

            // Invoice Generation
            Route::get('invoices/export-pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.export_pdf');
            Route::get('invoices/generate', [InvoiceController::class, 'showGenerateForm'])->name('invoices.generate.form');
            Route::post('invoices/generate', [InvoiceController::class, 'processGenerate'])->name('invoices.generate.process');
            Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'storePayment'])->name('invoices.storePayment');
            Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
            // Standard Invoice Resource (List බලන්න, delete කරන්න)
            Route::resource('invoices', InvoiceController::class)->only([
                'index', 'show', 'destroy'
            ]);
            // Payroll Processing
            Route::get('payroll/export-pdf', [PayrollController::class, 'exportPdf'])->name('payroll.export_pdf');
            Route::get('payroll/process', [PayrollController::class, 'showProcessForm'])->name('payroll.process.form');
            Route::post('payroll/process', [PayrollController::class, 'processPayroll'])->name('payroll.process');

            // Payroll List & Payslip
            Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
            Route::delete('payroll/{payroll}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
            Route::get('payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
            Route::post('payroll/{payroll}/payments', [PayrollController::class, 'storePayment'])->name('payroll.payments.store');
            Route::get('payroll/{payroll}/print', [PayrollController::class, 'printPayslip'])->name('payroll.print');


            Route::get('reports/outstanding-fees', [ReportController::class, 'outstandingFees'])->name('reports.outstanding');
            Route::get('reports/salary-sheet', [ReportController::class, 'salarySheet'])->name('reports.salary_sheet');

            Route::get('reports/outstanding-fees/pdf', [ReportController::class, 'exportOutstandingFeesPdf'])->name('reports.outstanding.pdf');
            Route::get('reports/salary-sheet/pdf', [ReportController::class, 'exportSalarySheetPdf'])->name('reports.salary_sheet.pdf');
            Route::get('reports/attendance/daily/pdf', [ReportController::class, 'exportDailyAttendancePdf'])->name('reports.attendance.daily.pdf');
            Route::get('reports/attendance/class/pdf', [ReportController::class, 'exportClassAttendancePdf'])->name('reports.attendance.class.pdf');
            Route::get('reports/attendance/student/pdf', [ReportController::class, 'exportStudentAttendancePdf'])->name('reports.attendance.student.pdf');


            Route::post('invoices/{invoice}/mark-settled', [InvoiceController::class, 'markAsSettled'])->name('invoices.settle');
            // Print Route (Resource එකට උඩින් දාන්න)
            Route::get('expenses/print-report', [ExpenseController::class, 'printReport'])->name('expenses.print');
            // Other Expenses Routes
            Route::resource('expenses', ExpenseController::class);
            // View Receipt Page Route
            Route::get('expenses/{expense}/view-receipt', [ExpenseController::class, 'showReceipt'])
            ->name('expenses.view_receipt');
            // Category Management Route
            Route::resource('expense-categories', ExpenseCategoryController::class);

            Route::resource('grades', GradeController::class);
            Route::resource('sections', SectionController::class);
            Route::resource('students', StudentController::class);

            // 1. Assign Teacher Form එක පෙන්වන route (GET)
            Route::get('sections/{section}/assign-teacher', [SectionController::class, 'showAssignTeacherForm'])->name('sections.assign_teacher.form');

            // 2. Teacher ව assign කරලා save කරන route (POST)
            Route::post('sections/{section}/assign-teacher', [SectionController::class, 'storeAssignTeacher'])->name('sections.assign_teacher.store');

            // 3. Teacher ව අයින් කරන (Remove) route (DELETE)
            Route::delete('sections/{section}/remove-teacher', [SectionController::class, 'removeAssignTeacher'])->name('sections.assign_teacher.remove');
    });


    Route::middleware(['auth', 'role:admin|teacher'])
            ->prefix('attendance')
            ->name('attendance.')
            ->group(function () {
            Route::get('reports/attendance/daily', [ReportController::class, 'dailyAttendance'])->name('reports.attendance.daily');


            Route::get('reports/attendance/student', [ReportController::class, 'studentMonthlyAttendance'])->name('reports.attendance.student');

            Route::get('reports/attendance/class', [ReportController::class, 'classAttendance'])->name('reports.attendance.class');

    });

require __DIR__.'/auth.php';
