<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIpRange;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use Input;
use Auth;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckIpRange::class);
    }

    public function showLoginAdminForm()
    {
        if (Auth::guard('admin')->id()) {
            return redirect()->route('user_managerment');
        }

        return view('admins.login_admin');
    }

    public function loginAsAdmin()
    {
        $rules = array(
            'email'    => 'required|email',
            'password' => 'required|min:8'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin_login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $userdata = array(
                'email'    => Input::get('email'),
                'password' => Input::get('password'),
                'del_flg'  => 0
            );

            if (Auth::guard('admin')->attempt($userdata)) {
                return redirect()->route('user_managerment');
            } else {
                return Redirect::to('admin')->with('error', __('login_admin.login_error'));
            }
        }
    }

    public function logOut()
    {
        if (Auth::guard('admin')->id()) {
            Auth::guard('admin')->logout();
        }

        return redirect()->route('admin_login');
    }
}
