<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ChairpersonController;
use App\Http\Controllers\ChairpersonDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TaskApiController;



Route::middleware(['auth'])->group(function () {
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    // routes/web.php

});

// Add this to your existing routes
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/unauthorized2', [UserController::class, 'redirec'])->name('redirect');

Route::get('/unauthorized', function () {
    return view('unauthorizedpage');
})->name('unauthorized');


Route::get('/test1', [EmployeeController::class, 'myTasks'])->name('sad');
Route::get('/test2', [\App\Http\Controllers\Test::class, 'test'])->name('sad');
Route::get('/test3', [\App\Http\Controllers\Test::class, 'test1'])->name('sad');
Route::get('/test4', [\App\Http\Controllers\Test::class, 'test2'])->name('sad');
Route::get('/test6', [\App\Http\Controllers\Test::class, 'test4'])->name('sad');
Route::get('/test5', [\App\Http\Controllers\Test::class, 'test3'])->name('sad');


Route::middleware(['auth','role:Employee'])->group(function () {
    // Employee Task Routes
    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
    Route::get('/my-tasks', [EmployeeController::class, 'myTasks'])->name('employee.tasks');
    Route::get('/tasks/{id}/details', [EmployeeController::class, 'taskDetails']);
    Route::post('/tasks/{id}/complete', [EmployeeController::class, 'markComplete']);
    Route::post('/tasks/submit', [EmployeeController::class, 'submitTask']);
    Route::get('/employeesettings', [ProfileController::class, 'showemployee'])->name('employee.setting');
    Route::put('/', [ProfileController::class, 'update'])->name('employee.profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('employee.profile.password');
    Route::put('/notifications', [ProfileController::class, 'updateNotifications'])->name('employee.profile.notifications');
});

Route::get('/taskView', [ChairpersonDashboardController::class, 'show'])->name('show.tasks');

// Route::prefix('chairperson')->middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [ChairpersonController::class, 'dashboard'])->name('chair.dashboard');
//     Route::get('/assigntask', [ChairpersonController::class, 'index'])->name('chair.assigntask');
//     Route::post('/storetask', [ChairpersonController::class, 'store'])->name('store.task');

// // API routes for task data
// Route::get('/api/tasks', [App\Http\Controllers\TaskApiController::class, 'getTasks'])->name('api.tasks');
// Route::get('/api/tasks/stats', [App\Http\Controllers\TaskApiController::class, 'getTaskStats'])->name('api.tasks.stats');


// });


//test

// Add these routes to your routes/web.php file

// Employee dashboard and task calendar







// API routes for task data
Route::get('/api/tasks', [App\Http\Controllers\TaskApiController::class, 'getTasks'])->name('api.tasks');
Route::get('/api/tasks/stats', [App\Http\Controllers\TaskApiController::class, 'getTaskStats'])->name('api.tasks.stats');

// Task actions
Route::post('/tasks/{task}/complete', [App\Http\Controllers\TaskController::class, 'markComplete'])->name('tasks.complete');
Route::post('/tasks/{task}/reopen', [App\Http\Controllers\TaskController::class, 'reopen'])->name('tasks.reopen');
Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');

//test



Route::prefix('admin')->middleware(['auth','role:Admin'])->group(function () {
    Route::get('/members', [UserController::class, 'index'])->name('task.members');
    Route::get('/assigntask', [TaskController::class, 'create'])->name('assign.task');
    Route::get('/alltask', [TaskController::class, 'index'])->name('task.index');
    Route::get('/documents', [AttachmentController::class, 'index'])->name('task.document');
    Route::get('/dashboard', [TaskController::class, 'dash'])->name('task.dashboard');
    Route::get('/reports', [TaskController::class, 'reports'])->name('task.report');
    // routes/web.php
    // Route to list attachment

    // Route to store attachments
    Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');

    // Route to download an attachment
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');

    // Route to delete an attachment
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::resource('users', UserController::class);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::post('/tasks/{task}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');
    Route::post('/tasks/{task}/reopen', [TaskController::class, 'reopen'])->name('tasks.reopen');
    Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
});
Auth::routes();
Route::get('/download/{path}', function ($path) {
    if (!Storage::exists($path)) {
        abort(404, 'File not found.');
    }

    return Storage::download($path);
})->name('download');


// Task Management Routes
Route::post('/tasks/{task}/approve/{user}', [ChairpersonDashboardController::class, 'approveAssignee'])
    ->name('chairperson.tasks.approve-assignee');

Route::post('/tasks/{task}/reject/{user}', [ChairpersonDashboardController::class, 'rejectAssignee'])
    ->name('chairperson.tasks.reject-assignee');



Route::prefix('chairperson')->middleware(['auth','role:Chairperson'])->group(function () {
    Route::get('/tasks', [ChairpersonDashboardController::class, 'taskManagement'])->name('chairperson.tasks');
    Route::get('/tasks/pending-approval', [ChairpersonDashboardController::class, 'pendingApprovals'])->name('chairperson.pending-approvals');
    Route::get('/tasks/review/{task}', [ChairpersonDashboardController::class, 'reviewTask'])->name('chairperson.tasks.review');
    Route::post('/tasks/approve/{task}', [ChairpersonDashboardController::class, 'approveTask'])->name('chairperson.tasks.approve');
    Route::post('/tasks/reject/{task}', [ChairpersonDashboardController::class, 'rejectTask'])->name('chairperson.tasks.reject');
    Route::get('/tasks/management', [ChairpersonDashboardController::class, 'taskManagement'])
        ->name('chairperson.task-management');

    Route::get('/assigntask', [ChairpersonDashboardController::class, 'create'])->name('chairassign.task');
    Route::post('/storetask', [ChairpersonDashboardController::class, 'store'])->name('store.task');


    Route::post('/tasks/{task}/approve/{user}', [ChairpersonDashboardController::class, 'approveAssignee'])
        ->name('chairperson.tasks.approve-assignee');

    Route::post('/tasks/{task}/reject/{user}', [ChairpersonDashboardController::class, 'rejectAssignee'])
        ->name('chairperson.tasks.reject-assignee');
    // Route::get('/settings', [ChairpersonDashboardController::class, 'settings'])->name('chairperson.setting');
    Route::get('/dashboard', [ChairpersonDashboardController::class, 'index'])->name('chairperson.dashboard');
    Route::get('/pending-approvals', [ChairpersonDashboardController::class, 'pendingApprovals'])->name('tasks.pending');
    Route::get('/tasks/{id}/review', [ChairpersonDashboardController::class, 'reviewTask'])->name('tasks.review');
    Route::post('/tasks/{id}/approve', [ChairpersonDashboardController::class, 'approveTask'])->name('tasks.approve');
    Route::post('/tasks/{id}/reject', [ChairpersonDashboardController::class, 'rejectTask'])->name('tasks.reject');
    Route::get('/team-performance', [ChairpersonDashboardController::class, 'teamPerformance'])->name('team.performance');
    Route::get('/upcoming-tasks', [ChairpersonDashboardController::class, 'upcomingTasks'])->name('tasks.upcoming');
    Route::get('/activity-log', [ChairpersonDashboardController::class, 'activityLog'])->name('activity.index');
    Route::post('/tasks/{task}/approve/{user}', [ChairpersonDashboardController::class, 'approveAssignee'])
        ->name('tasks.approve.assignee');
    Route::post('/tasks/{task}/reject/{user}', [ChairpersonDashboardController::class, 'rejectAssignee'])
        ->name('tasks.reject.assignee');




    Route::get('/settings', [ProfileController::class, 'show'])->name('chairperson.setting');
    Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');
});






Route::get('/profile', [ProfileController::class, 'show'])->name('departments.store');