<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassLevelController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DisciplineLogController;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\DormitoryController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCheckoutController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceBookingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\AnnouncementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/dashboard', function () {
    $userRole = auth()->user()->role->value;
    if ($userRole === 'student') {
        return redirect()->route('student.dashboard');
    }
    if ($userRole === 'parent') {
        return redirect()->route('parent.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management
    Route::group(['middleware' => ['role:root,headteacher']], function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('class-levels', ClassLevelController::class);
        Route::resource('class-levels.streams', StreamController::class)->except(['show'])->shallow();
        Route::resource('subjects', SubjectController::class);
        Route::get('teacher-assignments/create', [TeacherAssignmentController::class, 'create'])->name('teacher-assignments.create');
        Route::post('teacher-assignments', [TeacherAssignmentController::class, 'store'])->name('teacher-assignments.store');
        Route::get('student-assignments', [StudentAssignmentController::class, 'index'])->name('student-assignments.index');
        Route::post('student-assignments', [StudentAssignmentController::class, 'store'])->name('student-assignments.store');
    });

    // Student Management
    Route::group(['middleware' => ['role:root,headteacher']], function () {
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
        // The routes for DisciplineLog, HealthRecord, and Alumni were removed as their controllers do not exist yet.
        Route::get('students/{user}/report-card/{stream}', [StudentController::class, 'generateReportCard'])->name('students.report-card');
        Route::get('students/{user}/id-card', [StudentController::class, 'generateIdCard'])->name('students.id-card');
    });

    // Financial Management
    Route::group(['middleware' => ['role:root,headteacher,bursar']], function () {
        Route::resource('fee-categories', FeeCategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('expense-categories', ExpenseCategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('fee-structures', FeeStructureController::class);
        Route::resource('invoices', InvoiceController::class)->except(['edit', 'update']);
        Route::get('invoices/{invoice}/export/pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.export.pdf');
        Route::get('invoices/{invoice}/export/excel', [InvoiceController::class, 'exportExcel'])->name('invoices.export.excel');
        Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('invoices.payments.store');
        Route::resource('expenses', ExpenseController::class);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/outstanding-balances', [ReportController::class, 'outstandingBalances'])->name('reports.outstanding-balances');
        Route::get('reports/payment-summaries', [ReportController::class, 'paymentSummaries'])->name('reports.payment-summaries');
        Route::get('reports/income-vs-expenditure', [ReportController::class, 'incomeVsExpenditure'])->name('reports.income-vs-expenditure');
    });

    // Document Generation
    Route::group(['middleware' => ['role:root,headteacher'], 'prefix' => 'documents', 'as' => 'documents.'], function () {
        Route::get('select-id-card', [DocumentController::class, 'selectIdCard'])->name('id-card.select');
        Route::post('generate-id-card', [DocumentController::class, 'generateIdCard'])->name('id-card.generate');
        Route::get('select-report-card', [DocumentController::class, 'selectReportCard'])->name('report-card.select');
        Route::post('generate-report-card', [DocumentController::class, 'generateReportCard'])->name('report-card.generate');
    });

    // Admin-only
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:root,headteacher']], function () {
        // Routes for AuditLog and Alumni were removed as their controllers do not exist yet.
        Route::get('/chat', [ChatAdminController::class, 'index'])->name('chat.index');
        Route::get('/chat/{channel}', [ChatAdminController::class, 'showConversation'])->name('chat.show');
        Route::delete('/chat/messages/{messageId}', [ChatAdminController::class, 'forceDelete'])->name('chat.messages.delete');
    });

    // Teacher-specific
    Route::group(['middleware' => 'role:teacher,headteacher'], function() {
        Route::get('marks', [MarkController::class, 'index'])->name('marks.index');
        Route::get('marks/enter/{paper_stream_user_id}', [MarkController::class, 'enter'])->name('marks.enter');
        Route::post('marks', [MarkController::class, 'store'])->name('marks.store');
        Route::resource('exams', ExamController::class)->only(['index', 'create', 'store']);
    });

    // General authenticated routes
    Route::get('videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('teacher/chat', [ChatController::class, 'index'])->name('teacher.chat.index');

});

require __DIR__.'/auth.php';
