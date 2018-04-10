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

Route::get('/admin/user-managerment', 'Admin\AdminController@showUserManagerment')->name('user-managerment');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

