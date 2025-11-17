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
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
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
            Route::resource('grades', GradeController::class);
            Route::resource('sections', SectionController::class);
            Route::resource('students', StudentController::class);
            Route::resource('staff', StaffController::class);

            Route::get('staff/{staff}/payroll', [StaffPayrollController::class, 'edit'])->name('staff.payroll.edit');
            Route::post('staff/{staff}/allowances', [AllowanceController::class, 'store'])->name('staff.allowances.store');
            Route::delete('allowances/{allowance}', [AllowanceController::class, 'destroy'])->name('allowances.destroy');

            // Routes for Staff Deductions
            Route::post('staff/{staff}/deductions', [DeductionController::class, 'store'])->name('staff.deductions.store');
            Route::delete('deductions/{deduction}', [DeductionController::class, 'destroy'])->name('deductions.destroy');
            // 1. Assign Teacher Form එක පෙන්වන route (GET)
            Route::get('sections/{section}/assign-teacher', [SectionController::class, 'showAssignTeacherForm'])->name('sections.assign_teacher.form');

            // 2. Teacher ව assign කරලා save කරන route (POST)
            Route::post('sections/{section}/assign-teacher', [SectionController::class, 'storeAssignTeacher'])->name('sections.assign_teacher.store');

            // 3. Teacher ව අයින් කරන (Remove) route (DELETE)
            Route::delete('sections/{section}/remove-teacher', [SectionController::class, 'removeAssignTeacher'])->name('sections.assign_teacher.remove');
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
        Route::middleware(['auth', 'role:admin,accountant'])
            ->prefix('finance')
            ->name('finance.')
            ->group(function () {

            // Fee Categories CRUD
            Route::resource('fee-categories', FeeCategoryController::class);
            Route::resource('fee-structures', FeeStructureController::class);

            // Invoice Generation
            Route::get('invoices/generate', [InvoiceController::class, 'showGenerateForm'])->name('invoices.generate.form');
            Route::post('invoices/generate', [InvoiceController::class, 'processGenerate'])->name('invoices.generate.process');
            Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'storePayment'])->name('invoices.storePayment');
            // Standard Invoice Resource (List බලන්න, delete කරන්න)
            Route::resource('invoices', InvoiceController::class)->only([
                'index', 'show', 'destroy'
            ]);
            // Payroll Processing
            Route::get('payroll/process', [PayrollController::class, 'showProcessForm'])->name('payroll.process.form');
            Route::post('payroll/process', [PayrollController::class, 'processPayroll'])->name('payroll.process');

            // Payroll List & Payslip
            Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
            Route::get('payroll/{payroll}/payslip', [PayrollController::class, 'showPayslip'])->name('payroll.payslip');
            Route::delete('payroll/{payroll}', [PayrollController::class, 'destroy'])->name('payroll.destroy');

            Route::post('payroll/{payroll}/toggle-status', [PayrollController::class, 'toggleStatus'])->name('payroll.toggleStatus');
            Route::get('reports/outstanding-fees', [ReportController::class, 'outstandingFees'])->name('reports.outstanding');
            Route::get('reports/salary-sheet', [ReportController::class, 'salarySheet'])->name('reports.salary_sheet');
    });

require __DIR__.'/auth.php';
