<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassLevelController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('role:root,headteacher');

    // Class Level Management
    Route::resource('class-levels', ClassLevelController::class)
        ->middleware('role:root,headteacher');

    // Stream Management (nested under class levels)
    Route::resource('class-levels.streams', StreamController::class)
        ->except(['show'])
        ->shallow()
        ->middleware('role:root,headteacher');

    // Subject Management
    Route::resource('subjects', SubjectController::class)
        ->middleware('role:root,headteacher');

    // Paper Management
    Route::get('subjects/{subject}/manage-papers', [SubjectController::class, 'managePapers'])->name('subjects.manage-papers')->middleware('role:root,headteacher');
    Route::post('subjects/{subject}/manage-papers', [SubjectController::class, 'storePapers'])->name('subjects.store-papers')->middleware('role:root,headteacher');

    // Teacher Assignment
    Route::get('teacher-assignments/create', [TeacherAssignmentController::class, 'create'])->name('teacher-assignments.create')->middleware('role:root,headteacher');
    Route::post('teacher-assignments', [TeacherAssignmentController::class, 'store'])->name('teacher-assignments.store')->middleware('role:root,headteacher');

    // Mark Entry
    Route::get('marks', [MarkController::class, 'index'])->name('marks.index')->middleware('role:teacher,headteacher');
    Route::get('marks/enter/{paper_stream_user_id}', [MarkController::class, 'enter'])->name('marks.enter')->middleware('role:teacher,headteacher');
    Route::post('marks', [MarkController::class, 'store'])->name('marks.store')->middleware('role:teacher,headteacher');

    // Student Assignment
    Route::get('student-assignments', [StudentAssignmentController::class, 'index'])->name('student-assignments.index')->middleware('role:root,headteacher');
    Route::post('student-assignments', [StudentAssignmentController::class, 'store'])->name('student-assignments.store')->middleware('role:root,headteacher');

    // Student Management
    Route::post('students/{student}/discipline-logs', [\App\Http\Controllers\DisciplineLogController::class, 'store'])->name('students.discipline-logs.store')->middleware('role:root,headteacher');
    Route::delete('discipline-logs/{disciplineLog}', [\App\Http\Controllers\DisciplineLogController::class, 'destroy'])->name('discipline-logs.destroy')->middleware('role:root,headteacher');
    Route::get('students', [StudentController::class, 'index'])->name('students.index')->middleware('role:root,headteacher');
    Route::get('students/{student}', [\App\Http\Controllers\StudentController::class, 'show'])->name('students.show')->middleware('role:root,headteacher');
    Route::get('students/{student}/health-record', [\App\Http\Controllers\HealthRecordController::class, 'edit'])->name('students.health-record.edit')->middleware('role:root,headteacher');
    Route::put('students/{student}/health-record', [\App\Http\Controllers\HealthRecordController::class, 'update'])->name('students.health-record.update')->middleware('role:root,headteacher');
    Route::get('students/search', [StudentController::class, 'search'])->name('students.search')->middleware('role:root,headteacher');
    Route::post('students/{user}/photo', [StudentController::class, 'updatePhoto'])->name('students.photo.update')->middleware('role:root,headteacher');
    Route::get('students/upload', [StudentController::class, 'showUploadForm'])->name('students.upload.form')->middleware('role:root,headteacher');
    Route::post('students/upload', [StudentController::class, 'import'])->name('students.import')->middleware('role:root,headteacher');
    Route::get('students/export/excel', [StudentController::class, 'exportExcel'])->name('students.export.excel')->middleware('role:root,headteacher');
    Route::get('students/export/pdf', [StudentController::class, 'exportPdf'])->name('students.export.pdf')->middleware('role:root,headteacher');
    Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template')->middleware('role:root,headteacher');
    Route::get('students/{user}/streams/{stream}/report-card', [StudentController::class, 'generateReportCard'])->name('students.report-card')->middleware('role:root,headteacher');
    Route::get('students/{user}/id-card', [StudentController::class, 'generateIdCard'])->name('students.id-card')->middleware('role:root,headteacher');

    // Fee Structure Management
    Route::resource('fee-structures', \App\Http\Controllers\FeeStructureController::class)
        ->middleware('role:root,headteacher,bursar');

    // Invoice Management
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class)
        ->except(['edit', 'update']) // Invoices are generated, not edited in a traditional sense.
        ->middleware('role:root,headteacher,bursar');
    Route::get('invoices/{invoice}/export/pdf', [\App\Http\Controllers\InvoiceController::class, 'exportPdf'])->name('invoices.export.pdf')->middleware('role:root,headteacher,bursar');
    Route::get('invoices/{invoice}/export/excel', [\App\Http\Controllers\InvoiceController::class, 'exportExcel'])->name('invoices.export.excel')->middleware('role:root,headteacher,bursar');

    // Payment Recording
    Route::post('invoices/{invoice}/payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('invoices.payments.store')->middleware('role:root,headteacher,bursar');

    // Expense Tracking
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class)
        ->middleware('role:root,headteacher,bursar');

    // Financial Reports
    Route::group(['prefix' => 'reports', 'as' => 'reports.', 'middleware' => ['auth', 'role:root,headteacher,bursar']], function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/outstanding-balances', [\App\Http\Controllers\ReportController::class, 'outstandingBalances'])->name('outstanding-balances');
        Route::get('/payment-summaries', [\App\Http\Controllers\ReportController::class, 'paymentSummaries'])->name('payment-summaries');
        Route::get('/income-vs-expenditure', [\App\Http\Controllers\ReportController::class, 'incomeVsExpenditure'])->name('income-vs-expenditure');
    });

    // Hostel Management
    Route::group(['middleware' => ['auth', 'role:root,headteacher']], function () {
        Route::resource('dormitories', \App\Http\Controllers\DormitoryController::class);
        Route::post('dormitories/{dormitory}/rooms', [\App\Http\Controllers\DormitoryController::class, 'storeRoom'])->name('dormitories.rooms.store');
        Route::delete('dormitory-rooms/{room}', [\App\Http\Controllers\DormitoryController::class, 'destroyRoom'])->name('dormitory-rooms.destroy');
        Route::resource('room-assignments', \App\Http\Controllers\RoomAssignmentController::class)->except(['show', 'edit', 'update']);
    });

    // Activities Management
    Route::group(['middleware' => ['auth', 'role:root,headteacher']], function () {
        Route::resource('clubs', \App\Http\Controllers\ClubController::class);
        Route::post('clubs/{club}/members', [\App\Http\Controllers\ClubController::class, 'addMember'])->name('clubs.members.store');
        Route::delete('clubs/{club}/members/{student}', [\App\Http\Controllers\ClubController::class, 'removeMember'])->name('clubs.members.destroy');
    });

    // Communication
    Route::get('communications/create', [CommunicationController::class, 'create'])->name('communications.create')->middleware('role:root,headteacher');
    Route::post('communications', [CommunicationController::class, 'send'])->name('communications.send')->middleware('role:root,headteacher');

    // Attendance
    Route::get('attendance/qrcode', [AttendanceController::class, 'showQrCode'])->name('attendance.qrcode')->middleware('role:root,headteacher');
    Route::post('attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan')->middleware('auth');
    Route::get('attendance/records', [AttendanceController::class, 'records'])->name('attendance.records')->middleware('role:root,headteacher,bursar');

    // Video Content
    Route::get('videos', [VideoController::class, 'index'])->name('videos.index')->middleware('role:student,teacher,headteacher,root');
    Route::get('videos/upload', [VideoController::class, 'create'])->name('videos.create')->middleware('role:teacher');
    Route::post('videos', [VideoController::class, 'store'])->name('videos.store')->middleware('role:teacher');

    // Teacher Chat
    Route::get('teacher/chat', [ChatController::class, 'index'])->name('teacher.chat.index');
    Route::get('chat/group-messages', [ChatController::class, 'getGroupMessages'])->name('chat.group-messages');
    Route::post('teacher/chat/send', [ChatController::class, 'sendMessage'])->name('teacher.chat.send');

    // Direct Messages
    Route::get('/dm/{receiver}', [DirectMessageController::class, 'show'])->name('dm.show');
    Route::post('/dm/{receiver}', [DirectMessageController::class, 'store'])->name('dm.store');

    // Message Deletion
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Admin Chat Oversight
    Route::middleware('role:root')->group(function () {
        Route::get('/admin/chat', [ChatAdminController::class, 'index'])->name('admin.chat.index');
        Route::get('/admin/chat/{channel}', [ChatAdminController::class, 'showConversation'])->name('admin.chat.show');
        Route::delete('/admin/chat/messages/{messageId}', [ChatAdminController::class, 'forceDelete'])->name('admin.chat.messages.delete');
    });

    // Document Generation
    Route::get('/documents/id-card/select', [DocumentController::class, 'selectIdCard'])->name('documents.id-card.select');
    Route::post('/documents/id-card', [DocumentController::class, 'generateIdCard'])->name('documents.id-card.generate');
    Route::get('/documents/report-card/select', [DocumentController::class, 'selectReportCard'])->name('documents.report-card.select');
    Route::post('/documents/report-card', [DocumentController::class, 'generateReportCard'])->name('documents.report-card.generate');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

require __DIR__.'/auth.php';
