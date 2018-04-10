<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginAdminForm()
    {
        return view('auth.login_admin');
    }

    public function loginAsAdmin()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:3'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $userdata = array(
                'email'    => Input::get('email'),
                'password' => Input::get('password'),
                'del_flg'  => 0
            );

            if (Auth::guard('admin')->attempt($userdata)) {
                echo 'SUCCESS!';
                Auth::guard('admin')->login($userdata);
            } else {
                return Redirect::to('admin');
            }

        }
    }
}
