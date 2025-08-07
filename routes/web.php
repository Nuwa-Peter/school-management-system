<?php

use App\Http\Controllers\ClassLevelController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('role:root,headteacher')
        ->name('users.index');

    // Class Level Management
    Route::resource('class-levels', ClassLevelController::class)
        ->middleware('role:root,headteacher');

    // Subject Management
    Route::resource('subjects', SubjectController::class)
        ->middleware('role:root,headteacher');

    // Paper Management (nested under subjects)
    Route::resource('subjects.papers', PaperController::class)
        ->except(['show'])
        ->shallow()
        ->middleware('role:root,headteacher');

    // Teacher Assignment
    Route::get('teacher-assignments/create', [TeacherAssignmentController::class, 'create'])->name('teacher-assignments.create')->middleware('role:root,headteacher');
    Route::post('teacher-assignments', [TeacherAssignmentController::class, 'store'])->name('teacher-assignments.store')->middleware('role:root,headteacher');

    // Mark Entry
    Route::get('marks', [MarkController::class, 'index'])->name('marks.index')->middleware('role:teacher');
    Route::get('marks/enter/{paper_stream_user_id}', [MarkController::class, 'enter'])->name('marks.enter')->middleware('role:teacher');
    Route::post('marks', [MarkController::class, 'store'])->name('marks.store')->middleware('role:teacher');

    // Student Assignment
    Route::get('student-assignments', [StudentAssignmentController::class, 'index'])->name('student-assignments.index')->middleware('role:root,headteacher');
    Route::post('student-assignments', [StudentAssignmentController::class, 'store'])->name('student-assignments.store')->middleware('role:root,headteacher');
});

require __DIR__.'/auth.php';
