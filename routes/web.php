<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Settings\MenuController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;

use App\Http\Middleware\ForcePasswordChange;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Authenticaed routes should bound to auth middleware and guest as guest middleware.
 * @cloudy4next
 */

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginViewShow'])->name('login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/password-reset', [AuthController::class, 'passwordReset'])->name('password.reset');


});


Route::middleware('auth')->group(function () {

    Route::get("/main", [ThemeController::class, 'main']);

    Route::get('/logout', ['uses' => 'Auth\AuthController@logout', 'as' => 'logout'])->withoutMiddleware([ForcePasswordChange::class]);

    Route::get('/session-check', ['uses' => 'Auth\AuthController@test']);

    Route::get("/", [HomeController::class, 'main'])->name('home');




    Route::prefix('profile')->group(function () {
        Route::get("password/change", [UserController::class, 'passwordChange'])->name('password.change');
        Route::post("password/update", [UserController::class, 'passwordUpdate'])->name('password.update')->withoutMiddleware([ForcePasswordChange::class]);
    });

    Route::prefix('users')->group(function () {
        Route::get("/", [UserController::class, 'user'])->name('user_list')->middleware('acl:users');
        Route::get("/show/{id}", [UserController::class, 'show'])->name('user_detail')->middleware('acl:users');
        Route::get("/create", [UserController::class, 'create'])->name('user_create')->middleware('acl:users-create');
        Route::post("/save", [UserController::class, 'store'])->name('user_store')->middleware('acl:users-create');
        Route::get("/edit/{id}", [UserController::class, 'edit'])->name('user_edit')->middleware('acl:users-update');
        Route::post("/update", [UserController::class, 'update'])->name('user_update')->middleware('acl:users-update');
        Route::get("/delete/{id}", [UserController::class, 'delete'])->name('user_delete')->middleware('acl:users-delete');

    });

    Route::prefix('permission')->group(function () {
        Route::get("/", [PermissionController::class, 'permission'])->name('permission_list')->middleware('acl:permission');
        Route::get("/show/{id}", [PermissionController::class, 'show'])->name('permission_detail')->middleware('acl:permission');
        Route::get("/edit/{id}", [PermissionController::class, 'edit'])->name('permission_edit')->middleware('acl:permission-update');
        Route::get("/create", [PermissionController::class, 'create'])->name('permission_create')->middleware('acl:permission-create');
        Route::post("/save", [PermissionController::class, 'store'])->name('permission_store')->middleware('acl:permission-create');
        Route::post("/update", [PermissionController::class, 'update'])->name('permission_update')->middleware('acl:permission-create');
        Route::get("/delete/{id}", [PermissionController::class, 'delete'])->name('permission_delete')->middleware('acl:permission-delete');
    });

    Route::prefix('role')->group(function () {
        Route::get("/", [RoleController::class, 'role'])->name('role_list')->middleware('acl:role');
        Route::get("/show/{id}", [RoleController::class, 'show'])->name('role_detail')->middleware('acl:role');
        Route::get("/create", [RoleController::class, 'create'])->name('role_create')->middleware('acl:role-create');
        Route::get("/edit/{id}", [RoleController::class, 'edit'])->name('role_edit')->middleware('acl:role-update');
        Route::post("/save", [RoleController::class, 'store'])->name('role_store')->middleware('acl:role-create');
        Route::post("/update", [RoleController::class, 'update'])->name('role_update')->middleware('acl:role-update');
        Route::get("/delete/{id}", [RoleController::class, 'delete'])->name('role_delete')->middleware('acl:role-delete');
    });
    Route::prefix('menu')->group(function () {
        Route::get("/", [MenuController::class, 'menu'])->name('menu_list')->middleware('acl:menu');
        Route::get("/show/{id}", [MenuController::class, 'show'])->name('menu_detail')->middleware('acl:menu');
        Route::get("/edit/{id}", [MenuController::class, 'edit'])->name('menu_edit')->middleware('acl:menu-update');
        Route::get("/create", [MenuController::class, 'create'])->name('menu_create')->middleware('acl:menu-create');
        Route::post("/save", [MenuController::class, 'store'])->name('menu_store')->middleware('acl:menu-create');
        Route::post("/update", [MenuController::class, 'update'])->name('menu_update')->middleware('acl:menu-update');
        Route::get("/delete/{id}", [MenuController::class, 'delete'])->name('menu_delete')->middleware('acl:menu-delete');
    });

    Route::prefix('customer')->group(function () {

        Route::get("/", [HomeController::class, 'customer'])->name('customer_list');
        Route::get("/create", [HomeController::class, 'create'])->name('customer_create');
        Route::post("/save", [HomeController::class, 'store'])->name('customer_save');
        Route::get("/show/{id}", [HomeController::class, 'show'])->name('customer_detail');
        Route::get("/edit/{id}", [HomeController::class, 'edit'])->name('customer_edit');
        Route::get("/delete/{id}", [HomeController::class, 'delete'])->name('customer_delete');
        Route::get("/custom/{name}", [HomeController::class, 'custom'])->name('customer_custom');
    });

});
