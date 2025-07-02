<?php

use App\Models\SystemRole;
use App\Models\CompletionReport;
use App\Models\StudentInformation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SystemRoleController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FacultyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [AuthController::class, 'index'])->name('home');




Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [UserController::class, 'createuser'])->name('postregister');

//Admin Show Form Submit User
Route::get('/admin/formsubmitcourse', [AuthController::class, 'submitcourse'])->middleware('admincheck:moderator')->name('admincourse');
//Admin Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'show'])->middleware('admincheck:dashboard');

// Route::get('/preview-pdf/{filename}', [PDFController::class, 'previewPDF'])->name('preview.pdf');


//Admin User Management 

Route::get('/admin/users', [UserController::class, 'index'])->middleware('admincheck:users')->name('users');
Route::post('/admin/users/create', [UserController::class, 'createuser'])->middleware("admincheck:roles");
Route::post('/admin/users/update/{user:username}', [UserController::class, 'updateuser'])->where('username', '[A-Za-z0-9_\-]+')->middleware('admincheck:roles')->name('admin.users.update');
Route::get('/admin/users/edit/{user:username}', [UserController::class, 'edituser'])->where('username', '[A-Za-z0-9_\-]+')->middleware('admincheck:roles');
Route::get('/admin/users/lock/{user:username}', [UserController::class, 'lockuser'])->where('username', '[A-Za-z0-9_\-]+')->middleware('admincheck:roles');
Route::get('/admin/users/unlock/{user:username}', [UserController::class, 'unlockuser'])->where('username', '[A-Za-z0-9_\-]+')->middleware('admincheck:roles');
Route::get('/admin/users/delete/{user:username}', [UserController::class, 'deleteuser'])->where('username', '[A-Za-z0-9_\-]+')->middleware('admincheck:roles');

Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->middleware('guest')->name('page.login');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

//Profile 
Route::get('/profile/{user:username}', [HomeController::class, 'showprofile'])->where('user', '[A-z\d\-_]+')->middleware('auth');
Route::post('/editprofile', [AuthController::class, 'updateprofile'])->middleware('auth');


//Admin System Roles 

Route::get('/admin/roles', [SystemRoleController::class, 'index'])->middleware('admincheck:roles')->name('roles');
Route::post('/admin/roles/create', [SystemRoleController::class, 'createrole'])->middleware("admincheck:roles");
Route::post('/admin/roles/update/{role:role}', [SystemRoleController::class, 'updaterole'])->where('role', '[A-z\d\-_]+')->middleware('admincheck:roles');
Route::get('/admin/roles/edit/{role:role}', [SystemRoleController::class, 'editrole'])->where('role', '[A-z\d\-_]+')->middleware('admincheck:roles');
Route::get('/admin/roles/delete/{role:role}', [SystemRoleController::class, 'deleterole'])->where('role', '[A-z\d\-_]+')->middleware('admincheck:roles');

//Department Start

//User Side
Route::get('/departments', [DepartmentController::class, 'showDepartments'])->name('user.departments');
Route::get('/departments/show/{department:id}', [DepartmentController::class, 'showDepartmentsProfile'])->name('user.departments.show');

// Public search routes for department pages
Route::get('/teachers/search', [TeacherController::class, 'search'])->name('teachers.search');
Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
Route::get('/departments/search', [DepartmentController::class, 'search'])->name('departments.search');
Route::get('/faculty/search', [FacultyController::class, 'search'])->name('faculty.search');

//Admin Side
Route::prefix('/admin')->middleware('admincheck:departments')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    Route::get('/departments/show/{department:id}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::post('/departments/update/{department:id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::get('/departments/edit/{department:id}', [DepartmentController::class, 'edit'])->name('departments.adminshow');
    Route::get('/departments/delete/{department:id}', [DepartmentController::class, 'destroy'])->name('departments.delete');
});


Route::get('/students', [StudentController::class, 'userShow'])->name('userstudents');
//Student Start
Route::prefix('/admin')->middleware('admincheck:students')->group(function () {
    // Move 'create' before dynamic {student:id} route
    Route::get('/students', [StudentController::class, 'index'])->name('students');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create'); // move this up
    Route::get('/students/edit/{student:id}', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');
    Route::post('/students/update/{student:id}', [StudentController::class, 'update'])->name('students.update');
    Route::get('/students/delete/{student:id}', [StudentController::class, 'destroy'])->name('students.delete');
    Route::get('/students/{student:id}', [StudentController::class, 'show'])->name('students.show'); // dynamic last
});

Route::get('/teachers', [TeacherController::class, 'userShow'])->name('userteachers');
Route::get('/faculty', [FacultyController::class, 'userShow'])->name('userfaculty');
Route::get('/students/{student:id}', [StudentController::class, 'showStudents'])->name('students.usershow'); // dynamic last
//Teacher Start
Route::prefix('/admin')->middleware('admincheck:teachers')->group(function () {
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers/store', [TeacherController::class, 'store'])->name('teachers.store');
    Route::post('/teachers/update/{teacher:id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::get('/teachers/edit/{teacher:id}', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::get('/teachers/delete/{teacher:id}', [TeacherController::class, 'destroy'])->name('teachers.delete');
    Route::get('/teachers/{teacher:id}', [TeacherController::class, 'show'])->name('teachers.show');
});
Route::get('/teachers/{teacher:id}', [TeacherController::class, 'showTeachers'])->name('teachers.usershow');

//Faculty Routes - Public
Route::get('/faculty/{faculty:id}', [FacultyController::class, 'showFaculty'])->name('faculty.usershow');

//Faculty Routes - Admin
Route::prefix('/admin')->middleware('admincheck:faculty')->group(function () {
    Route::get('/faculty', [FacultyController::class, 'index'])->name('faculty');
    Route::get('/faculty/create', [FacultyController::class, 'create'])->name('faculty.create');
    Route::post('/faculty/store', [FacultyController::class, 'store'])->name('faculty.store');
    Route::post('/faculty/update/{faculty:id}', [FacultyController::class, 'update'])->name('faculty.update');
    Route::get('/faculty/edit/{faculty:id}', [FacultyController::class, 'edit'])->name('faculty.edit');
    Route::get('/faculty/delete/{faculty:id}', [FacultyController::class, 'destroy'])->name('faculty.delete');
    Route::get('/faculty/{faculty:id}', [FacultyController::class, 'show'])->name('faculty.show');
});