<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\CheckIpRange;
use App\Http\Middleware\CheckResetPassword;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Input;
use Hash;
use Auth;
use Laravel\Passport\Client;
use Validator;

class UserController extends Controller
{
    protected $userRepository;
    protected $userClientRelationRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserClientRelationRepositoryInterface $userClientRelationRepository
    ){
        $this->middleware(CheckIpRange::class);
        $this->middleware('auth');

        $this->userRepository = $userRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
    }

    public function showChangePassword()
    {
        Session::put('menu', 'change_password');
        return view('users.change_password');
    }

    public function changePassword(Request $request)
    {
        $inputs = Input::get();

        if (!(Hash::check($inputs['current_password'], Auth::user()->password))) {
            // The passwords matches
            return back()->with("error_current_password", __('change_password.error_current_password'));
        }

        if(strcmp($inputs['current_password'], $inputs['new_password']) == 0){
            //Current password and new password are same
            return back()->with("error_new_password", __('change_password.error_new_password'));
        }

        if(!strcmp($inputs['new_password'], $inputs['confirm_new_password']) == 0){
            //Current password and new password are same
            return back()->with("error_confirm_new_password", __('change_password.error_confirm_new_password'));
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
        ]);

        if ($validator->fails()) {
            return back()->with('error_change_password',  __('change_password.error_change_password'));
        }

        //Change Password
        $user           = Auth::user();
        $user->password = bcrypt($inputs['new_password']);
        $user->save();

        return back()->with("success", __('change_password.success'));
    }

    public function showFormResetPassword()
    {
        return view('users.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $inputs = Input::get();

        if(!strcmp($inputs['new_password'], $inputs['confirm_new_password']) == 0){
            //Current password and new password are same
            return back()->with("error_confirm_new_password", __('change_password.error_confirm_new_password'));
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
        ]);

        if ($validator->fails()) {
            return back()->with('error_change_password', __('change_password.error_change_password'));
        }

        //Change Password
        $user                     = Auth::user();
        $user->password           = bcrypt($inputs['new_password']);
        $user->reset_password_flg = User::RESETTED_PASSWORD_FLG;
        $user->save();

        return redirect()->route('change_password')->with("success", __('change_password.success'));
    }

    public function logOut()
    {
        Auth::logout();

        return redirect()->route('user_login');
    }

    public function profile() {
        Session::put('menu', 'user_profile');
        $user = Auth::user();
        $client_ids = array_column($this->userClientRelationRepository->finds(['user_id' => $user->id], ['client_id'])->toArray(), 'client_id');
        $client_apps = Client::whereIn('id', $client_ids)->get();

        return view('users.profile', [
            'user' => $user,
            'client_apps' => $client_apps
        ]);
    }
}
