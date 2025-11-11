<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboardController;
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
            // Admin ගේ අනිත් routes මෙතන
        });

    // Accountant Dashboard
    Route::middleware(['role:accountant'])
        ->prefix('accountant')
        ->name('accountant.')
        ->group(function () {
            Route::get('/dashboard', [AccountantDashboardController::class, 'index'])->name('dashboard');
            // Accountant ගේ අනිත් routes මෙතන
        });

    // Teacher Dashboard
    Route::middleware(['role:teacher'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {
            Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
            // Teacher ගේ අනිත් routes මෙතන
        });


require __DIR__.'/auth.php';
