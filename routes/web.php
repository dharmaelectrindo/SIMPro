<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TemplateController;

Auth::routes();

Route::post('/login', function ()
{
    $credentials = request()->only('username', 'password');
 
    if (auth()->attempt($credentials))
    {
        auth()->logoutOtherDevices(request()->password);
 
        return redirect()->intended();
    }
    return back()->withStatus('Invalid credentials');
 
});


// HOME //
Route::get('/', [HomeController::class, 'index'])->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Login
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');
Route::post('change-password', [UserController::class, 'change_password'])->name('change.password');

// Roles
Route::resource('roles', RoleController::class)->middleware('auth');
Route::get('/roles/{roles}/assign-permissions', [RoleController::class, 'assignPermissionToRole'])->name('roles.assign-permissions');
Route::post('/roles/give-permissions', [RoleController::class, 'givePermissionToRole'])->name('roles.give-permissions');


// Permission
Route::resource('permissions', PermissionController::class);

// User
Route::resource('users', UserController::class)->middleware('auth');
Route::get('/users', [UserController::class, 'users'])->name('users.users');
Route::post('/users/delete', [UserController::class, 'delete'])->name('users.delete');
Route::get('/getemployees', [UserController::class, 'getEmployees'])->name('users.getEmployees');
Route::get('/getEmployeeId', [UserController::class, 'getEmployeeId'])->name('users.getEmployeeId');
Route::get('/getorganizations', [UserController::class, 'getOrganizations'])->name('users.getOrganizations');
Route::get('/getroles', [UserController::class, 'getRoles'])->name('users.getRoles');

// Organization
Route::resource('organizations', OrganizationController::class);

//Employees
Route::resource('employees', EmployeeController::class)->middleware('auth');
Route::post('employee/import', [EmployeeController::class, 'import'])->name('employees.import')->middleware('auth');

//Templates
Route::resource('templates', TemplateController::class)->middleware('auth');



