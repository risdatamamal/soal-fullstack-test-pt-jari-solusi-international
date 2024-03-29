<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return view('home');
});


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', function () {
    return view('pages.register');
});

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/forget',  function () {
    return view('pages.forgot-password');
})->name('password.forget');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::group(['middleware' => 'auth'], function () {
    // logout route
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/clear-cache', [HomeController::class, 'clearCache']);

    // dashboard route
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    //only those have manage_user permission will get access
    Route::group(['middleware' => 'can:manage_user'], function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user/get-list', [UserController::class, 'getUserList']);
        Route::get('/user/create', [UserController::class, 'create']);
        Route::post('/user/create', [UserController::class, 'store'])->name('create-user');
        Route::get('/user/{id}', [UserController::class, 'edit']);
        Route::post('/user/update', [UserController::class, 'update']);
        Route::get('/user/delete/{id}', [UserController::class, 'delete']);

        Route::get('/books', [BookController::class, 'index']);
        Route::get('/book/get-list', [BookController::class, 'getBookList']);
        Route::get('/book/create', [BookController::class, 'create']);
        Route::post('/book/create', [BookController::class, 'store'])->name('create-book');
        Route::get('/book/{id}', [BookController::class, 'edit']);
        Route::post('/book/update', [BookController::class, 'update']);
        Route::get('/book/delete/{id}', [BookController::class, 'delete']);

        Route::get('/borrows', [BorrowController::class, 'index']);
        Route::get('/borrow/get-list', [BorrowController::class, 'getBorrowList']);
        Route::get('/borrow/create', [BorrowController::class, 'create']);
        Route::post('/borrow/create', [BorrowController::class, 'store'])->name('create-borrow');
        Route::get('/borrow/{id}', [BorrowController::class, 'edit']);
        Route::post('/borrow/update', [BorrowController::class, 'update']);
        Route::get('/borrow/delete/{id}', [BorrowController::class, 'delete']);
    });

    //only those have manage_role permission will get access
    Route::group(['middleware' => 'can:manage_role|manage_user'], function () {
        Route::get('/roles', [RolesController::class, 'index']);
        Route::get('/role/get-list', [RolesController::class, 'getRoleList']);
        Route::post('/role/create', [RolesController::class, 'create']);
        Route::get('/role/edit/{id}', [RolesController::class, 'edit']);
        Route::post('/role/update', [RolesController::class, 'update']);
        Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
    });


    //only those have manage_permission permission will get access
    Route::group(['middleware' => 'can:manage_permission|manage_user'], function () {
        Route::get('/permission', [PermissionController::class, 'index']);
        Route::get('/permission/get-list', [PermissionController::class, 'getPermissionList']);
        Route::post('/permission/create', [PermissionController::class, 'create']);
        Route::get('/permission/update', [PermissionController::class, 'update']);
        Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
    });

    // get permissions
    Route::get('get-role-permissions-badge', [PermissionController::class, 'getPermissionBadgeByRole']);

    // permission examples
    Route::get('/permission-example', function () {
        return view('permission-example');
    });
});
