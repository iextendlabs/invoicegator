<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\User\ClientController;
use App\Http\Controllers\Admin\PreviewController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\User\UserTaskController;
use App\Http\Controllers\userDashboard;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// --------------------------------  USER ROUTES ---------------------------------------------

// User (Developer) authenticated routes

Route::get('/', [IndexController::class, 'login'])->name('userLogin');
Route::get('/signup', [IndexController::class, 'index'])->name('signUp');

Route::post('/register/user', [IndexController::class, 'registerUser'])->name('user.register');
// Route::post('user/register', [IndexController::class, 'registerUser'])->name('user.register');
Route::post('login', [IndexController::class, 'loginPost'])->name('authenticate.user');

Route::get('user/dashboard', [IndexController::class, 'userDashboard'])->name('user.dashboard');
Route::get('logout', [IndexController::class, 'destroy'])->name('user.logout');

// change password
// Route::get('user/change-password', [IndexController::class, 'ChangePasswordForm'])->name('user.change.password');
Route::post('user/change-password', [IndexController::class, 'changePassword'])->name('user.change.password');

// User (Developer) Task Routes

// display project list on click of Create Task button (projects associated with developer)
Route::get('user/project-list', [UserTaskController::class, 'showProjectList'])->name('user.project.list');

Route::get('user/create-task/{pid}', [UserTaskController::class, 'showTasksForm'])->name('user.create.task');

// create task
Route::post('/task-created', [UserTaskController::class, 'storeTasks'])->name('user.store.task');

// show task details
Route::get('task-details/{id}', [UserTaskController::class, 'taskDetail'])->name('user.task.details');
// delete task
Route::post('user/task/delete/{id}', [UserTaskController::class, 'deleteTask'])->name('user.delete.task');

// create log
Route::get('create-log/{logId}', [UserTaskController::class, 'createLog'])->name('user.create.log');
Route::post('create-log', [UserTaskController::class, 'postLog'])->name('user.post.log');



// -----------------------------------User (Client) Routes-----------------------------------
Route::get('client-dashboard', [ClientController::class, 'clientDashboard'])->name('client.dashboard');
Route::get('client/project/{id}', [ClientController::class, 'exploreProject'])->name('client.project.tasks');

// check invoice
Route::get('check-invoice/{invoice_id}', [ClientController::class, 'checkInvoice'])->name('check.invoice');
// change password
Route::get('client/change-password', [ClientController::class, 'ChangePasswordForm'])->name('client.change.password');
Route::post('client/password-update', [ClientController::class, 'changePassword'])->name('client.password.update');


// client logout
Route::get('logout', [ClientController::class, 'clientLogout'])->name('client.logout');

Route::get('/client/invoices', [ClientController::class, 'displayInvoices'])->name('client.available.invoices.view');
Route::get('/client/invoice-detail', [ClientController::class, 'invoiceDetail'])->name('client.invoice.detail');

// --------------------------------  ADMIN ROUTES ---------------------------------------------
Route::post('/register', [AdminController::class, 'storeAdmin']);
Route::group(['middleware' => 'admin'], function () {
    // Route::middleware([AdminMiddleware::class])->group(function () {
    // Admin Routes
    Route::get('admin/signup', [AdminController::class, 'AdminsignUp'])->name('AdminsignUp');
    Route::post('admin/register', [AdminController::class, 'registerAdmin'])->name('register.admin');

    Route::get('admin/login', [AdminController::class, 'adminLogin'])->name('adminLogin');
    Route::post('admin/login', [AdminController::class, 'AdminLoginPost'])->name('adminAuthenticate');

    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('adminDashboard');

    Route::get('admin/logout', [AdminController::class, 'adminLogout'])->name('adminlogout');

    // Admin Task Routes

    // show create task form
    Route::get('admin/create-task', [TaskController::class, 'createTask'])->name('admin/create-task');

    // create task
    Route::post('admin/task-created', [TaskController::class, 'storeTasks'])->name('admin/storeTask');

    // showing task details
    Route::get('admin/tasks/{id}', [TaskController::class, 'editTask'])->name('admin/task-details');

    // update Task
    Route::put('task/update/{id}', [TaskController::class, 'updateTask'])->name('admin/update-task');

    // delete a record
    Route::post('admin/delete/task/{id}', [TaskController::class, 'deleteTask'])->name('admin/task-delete');


    // Project Routes

    // available projects
    Route::get('admin/projects', [AdminController::class, 'showProjects'])->name('admin/projects');
    Route::get('admin/create/project', [ProjectController::class, 'createProjectForm'])->name('admin/create/project');
    Route::post('admin/project/created', [ProjectController::class, 'createProjectPost'])->name('admin/project/created');
    Route::get('/project/{id}', [ProjectController::class, 'viewProject'])->name('view.project');
    // Route::get('admin/project/{id}', [ProjectController::class, 'projectDetail'])->name('project/details');

    // Create Task inside Project View
    Route::get('admin/project/{pid}/create-task', [ProjectController::class, 'createProjectTask'])->name('project/create-task');

    Route::get('admin/edit/project/{id}', [ProjectController::class, 'editProject'])->name('admin/project-edit');
    Route::get('explore/project/{id}', [ProjectController::class, 'exploreProject'])->name('explore/project');
    Route::post('admin/project-updated', [ProjectController::class, 'updateProject'])->name('project-updated');
    Route::post('admin/delete/project/{id}', [AdminController::class, 'deleteProject'])->name('admin/project-delete');


    // Task Log Routes
    Route::get('create-task-log', [TaskLogController::class, 'index'])->name('create.task.log');
    Route::post('store-task-log', [TaskLogController::class, 'createLog'])->name('store.task.log');

    // create log for specific TASK
    Route::get('task/tasklog/{id}', [TaskLogController::class, 'createSpecificTaskLog'])->name('create.task.taskLog');

    // explore task -> taskLogs
    Route::get('/admin/logs/task/{task_id}', [TaskLogController::class, 'exploreLogs'])->name('explore-task/logs');
    // delete a Task Log
    Route::get('admin/edit/log/{id}', [TaskLogController::class, 'editLog'])->name('admin.edit.log.form');
    Route::post('admin/edit/log', [TaskLogController::class, 'postEditLog'])->name('admin.edit.log');
    Route::get('admin/delete/log/{id}', [TaskLogController::class, 'deleteLog'])->name('admin.delete.log');

    // create a new developer (new user)

    Route::get('admin/create/user', [AdminController::class, 'createUserForm'])->name('create.user');
    Route::post('admin/user/created', [AdminController::class, 'createUser'])->name('admin.create.user');

    Route::post('admin/delete/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
    Route::get('admin/edit/user/{id}', [AdminController::class, 'editUser'])->name('admin.edit.user');
    Route::post('admin/update/user', [AdminController::class, 'UpdateUser'])->name('admin.update.user');

    // Change Admin Password
    Route::get('/admin/change-password', [AdminController::class, 'changePassForm'])->name('change.admin.password');
    Route::post('/admin/change-password', [AdminController::class, 'changeAdminPassword'])->name('admin.change.password');

    // work details Routes
    Route::get('admin/work-detail', [AdminController::class, 'workDetail'])->name('work.detail');
    Route::post('admin/work-detail', [AdminController::class, 'getWworkDate'])->name('get.datewise.project.data');
    Route::get('admin/task/work-detail/{id}', [AdminController::class, 'WorkDetailOfTask'])->name('admin.task.work.detail');


    // preview routes

    // project preview
    Route::get('admin/preview/{id}', [PreviewController::class, 'preview'])->name('projectPreview');
    // generate PDF 
    // Route::get('admin/generate-report/{id}', [PreviewController::class, 'generateReport'])->name('report.project.preview');

    // task preview
    Route::get('admin/preview-task/{id}', [PreviewController::class, 'previewTask'])->name('admin/preview-task');


    // Receipt Routes
    Route::post('admin/invoice-receipt', [InvoiceController::class, 'generateReceipt'])->name('admin.invoice.receipt');

    // change log status to PAID
    Route::get('admin/{logID}/invoice-generated', [TaskLogController::class, 'changeLogStatus'])->where(['id' => '[0-9]+'])->name('change.log.status');

    // Invoice Routes

    Route::get('admin/project/{id}/create-invoice', [InvoiceController::class, 'createInvoiceView'])->name('create.invoice.view');
    Route::post('/admin/invoice-detail', [InvoiceController::class, 'dateWiseDetail'])->name('project.invoice.detail');
    Route::post('/admin/fixed-amount-invoice', [InvoiceController::class, 'fixedAmountInvoice'])->name('fixed.amount.invoice');
    Route::post('/admin/fixed-hour-invoice', [InvoiceController::class, 'fixedHourInvoice'])->name('fixed.hour.invoice');

    Route::get('admin/task/{id}-invoice', [InvoiceController::class, 'viewTaskInvoice'])->name('taskInvoice');

    // delete invoice
    Route::get('delete-invoice/{id}', [InvoiceController::class, 'deleteInvoice'])->name('delete.invoice');
    // date wise invoice AJAX route
    Route::post('ajax-call', [AjaxController::class, 'dateWiseInvoice']);
    Route::post('ajax-entry', [AjaxController::class, 'dateWiseInvoiceEntry']);

    // view invoices
    Route::get('/invoices', [ProjectController::class, 'displayInvoices'])->name('available.invoices.view');
    Route::get('/invoice-detail', [ProjectController::class, 'invoiceDetail'])->name('invoice.detail');
});