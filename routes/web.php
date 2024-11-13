<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;


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
Route::post('/roles/delete', [RoleController::class, 'delete'])->name('roles.delete');
Route::get('/roles/add-permission/{roles}', [RoleController::class, 'addPermissionToRole'])->name('roles.add-permission');
Route::post('/roles/give-permission', [RoleController::class, 'givePermissionToRole'])->name('roles.give-permission');

// Permission
Route::resource('permissions', PermissionController::class);
Route::post('/permissions/delete', [PermissionController::class, 'delete'])->name('permissions.delete');

// User
Route::resource('users', UserController::class)->middleware('auth');
Route::get('/users', [UserController::class, 'users'])->name('users.users');
Route::post('/users/delete', [UserController::class, 'delete'])->name('users.delete');
Route::get('/getroles', [UserController::class, 'getRoles'])->name('users.getRoles');



