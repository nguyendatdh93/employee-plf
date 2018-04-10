<?php

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

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/admin', 'Admin\LoginController@showLoginAdminForm')->name('admin_login');
Route::post('/admin/login', 'Admin\LoginController@loginAsAdmin')->name('login_as_admin');
Route::get('/admin/logout', 'Admin\LoginController@logOut')->name('admin_logout');
Route::get('/admin/remove/{user_id?}', 'Admin\AdminController@removeUser')->name('remove-user');

Route::get('/admin/user-managerment', 'Admin\AdminController@showUserManagerment')->name('user-managerment');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('user')->group(function () {
    Route::get('/list', 'UserController@index')->name('all-user');

    Route::get('/add', 'UserController@addForm')->name('add-user-form');
    Route::post('/add', 'UserController@add')->name('add-user');

    Route::get('/edit/{id?}', 'UserController@editForm')->name('edit-user-form');
    Route::post('/edit/{id}', 'UserController@edit')->name('edit-user');

    Route::get('/forgot-password', 'UserController@forgotPasswordForm')->name('forgot-password-form');
    Route::post('/forgot-password', 'UserController@forgotPassword')->name('forgot-password');

    Route::get('/change-password', 'UserController@changePasswordForm')->name('change-password-form');
    Route::post('/change-password', 'UserController@changePassword')->name('change-password');

    Route::get('/remove/{id?}', 'UserController@remove')->name('remove-user');
});

