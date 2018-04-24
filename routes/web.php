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

Route::get('/expired_login', function () {
    return view('users.expired_login');
})->middleware(\App\Http\Middleware\CheckIpRange::class)->name('expired_login');

Route::prefix('admin')->group(function () {
    Route::get('', 'Admin\LoginController@showLoginAdminForm')->name('admin_login');
    Route::post('/login', 'Admin\LoginController@loginAsAdmin')->name('login_as_admin');
    Route::get('/logout', 'Admin\LoginController@logOut')->name('admin_logout');
    Route::get('/remove/{user_id?}/{user_email?}', 'Admin\AdminController@removeUser')->name('remove-user');

    Route::get('/user-management', 'Admin\AdminController@showUserManagerment')->name('user_management');

    Route::get('/add-user', 'Admin\AdminController@addUserForm')->name('add_user_form');
    Route::post('/add-user', 'Admin\AdminController@addUser')->name('add_user');

    Route::get('/edit-user/{id?}', 'Admin\AdminController@editUserForm')->name('edit_user_form');
    Route::post('/edit-user', 'Admin\AdminController@editUser')->name('edit_user');

    Route::get('/client-app-setting', 'Admin\AdminController@showClientAppSetting')->name('client_app_setting');
    Route::get('/create-new-client-app', 'Admin\AdminController@createClientAppForm')->name('create_client_app_form');
    Route::post('/create-new-client-app', 'Admin\AdminController@createClientApp')->name('create_client_app');
    Route::get('/remove-client-app/{client_app_id?}', 'Admin\AdminController@removeClientApp')->name('remove_client_app');
    Route::get('/edit-client-app/{client_app_id?}', 'Admin\AdminController@editClientAppForm')->name('edit_client_app_form');
    Route::post('/edit-client-app', 'Admin\AdminController@editClientApp')->name('edit_client_app');

    Route::get('/reset-expired-user/{id?}', 'Admin\AdminController@resetExpiredUser')->name('reset_expired_user');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('user')->group(function () {
    Route::get('/list', 'UserController@index')->name('all-user');

    Route::get('/forgot-password', 'UserController@forgotPasswordForm')->name('forgot-password-form');
    Route::post('/forgot-password', 'UserController@forgotPassword')->name('forgot-password');

    Route::get('/change-password', 'User\UserController@showFormChangePassword')->name('change_password_form');
    Route::post('/change-password', 'User\UserController@changePassword')->name('change_password');
    Route::get('/logout', 'User\UserController@logOut')->name('user_logout');
    Route::get('/reset-password', 'User\UserController@showFormResetPassword')->name('reset_password_form');
    Route::post('/reset-password', 'User\UserController@resetPassword')->name('reset_password');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('reset_forgot_password');
    Route::get('/profile', 'User\UserController@profile')->name('profile');
});

Route::get('/404', function(){
    return view('errors.404');
})->name('404');

Route::get('/lang', 'Controller@setupLanguage')->middleware(\App\Http\Middleware\CheckIpRange::class)->name('lang');

