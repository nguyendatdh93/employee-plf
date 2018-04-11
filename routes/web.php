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

Route::get('/', function (){
    return redirect('/login');
})->name('user_login');

Route::prefix('admin')->group(function () {
    Route::get('', 'Admin\LoginController@showLoginAdminForm')->name('admin_login');
    Route::post('/login', 'Admin\LoginController@loginAsAdmin')->name('login_as_admin');
    Route::get('/logout', 'Admin\LoginController@logOut')->name('admin_logout');
    Route::get('/remove/{user_id?}', 'Admin\AdminController@removeUser')->name('remove-user');

    Route::get('/user-managerment', 'Admin\AdminController@showUserManagerment')->name('user-managerment');
});

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

    Route::get('/change-password', 'User\UserController@showChangePassword')->name('change_password_form');
    Route::post('/change-password', 'User\UserController@changePassword')->name('change_password');
    Route::get('/logout', 'User\UserController@logOut')->name('user_logout');
    Route::get('/reset-password', 'User\UserController@showFormResetPassword')->name('reset_password_form');
    Route::post('/reset-password', 'User\UserController@resetPassword')->name('reset_password');

    Route::get('/remove/{id?}', 'UserController@remove')->name('remove-user');
});

