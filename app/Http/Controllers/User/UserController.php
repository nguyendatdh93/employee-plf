<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIpRange;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Input;
use Hash;
use Auth;
use Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository )
    {
        $this->middleware(CheckIpRange::class);
        $this->userRepository = $userRepository;
    }

    public function showChangePassword()
    {
        return view('users.change_password');
    }

    public function changePassword(Request $request)
    {
        $inputs = Input::get();

        if (!(Hash::check($inputs['current_password'], Auth::user()->password))) {
            // The passwords matches
            return back()->with("error_current_password","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($inputs['current_password'], $inputs['new_password']) == 0){
            //Current password and new password are same
            return back()->with("error_new_password","New Password cannot be same as your current password.");
        }

        if(!strcmp($inputs['new_password'], $inputs['confirm_new_password']) == 0){
            //Current password and new password are same
            return back()->with("error_confirm_new_password","Confirm new password cannot be same as new password.");
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
        ]);

        if ($validator->fails()) {
            return back()->with('error_change_password', 'Password changed not success.');
        }

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($inputs['new_password']);
        $user->save();

        return back()->with("success","Password changed successfully !");
    }
}
