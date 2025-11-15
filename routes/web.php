<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboardController;
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
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

    });

require __DIR__.'/auth.php';
