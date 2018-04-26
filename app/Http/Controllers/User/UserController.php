<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIpRange;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
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

        $this->userRepository               = $userRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFormChangePassword()
    {
        Session::put('menu', 'change_password');

        return view('users.change_password');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        try {
            $inputs = $request->all();

            if (!(Hash::check($inputs['current_password'], Auth::user()->password))) {
                return back()->withErrors(["current_password" => __('change_password.error_current_password')])->withInput();
            }

            $validator = Validator::make($inputs, [
                'current_password'     => 'required|string|min:8|max:50',
                'new_password'         => 'required|string|min:8|max:50|different:current_password',
                'confirm_new_password' => 'required_with:new_password|same:new_password|string|min:8|max:50',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('errors', $validator->messages())
                    ->withInput();
            }

            $user           = Auth::user();
            $user->password = bcrypt($inputs['new_password']);
            $user->save();

            return redirect()->route('profile')->with("success", __('change_password.success'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFormResetPassword()
    {
        return view('users.reset_password');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $inputs = $request->all();
            if (Hash::check($inputs['new_password'], Auth::user()->password)) {
                // The passwords matches
                return back()->withErrors(["new_password" => __('reset_password.error_current_password')])->withInput();
            }

            $validator = Validator::make($inputs, [
                'new_password'         => 'required|string|min:8|max:50',
                'confirm_new_password' => 'required_with:new_password|same:new_password|string|min:8|max:50',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('errors', $validator->messages())
                    ->withInput();
            }

            $user = Auth::user();
            $user->password           = bcrypt($inputs['new_password']);
            $user->reset_password_flg = User::RESET_PASSWORD_YES;
            $user->save();

            if (!empty(Session::get('third_party_login')) && Session::get('third_party_login')) {
                $redirect_route = Session::get('third_party_login');
                Session::put('third_party_login', '');

                return redirect($redirect_route);
            }

            return redirect()->route('profile')->with("success", __('reset_password.success'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logOut()
    {
        if (Auth::user()) {
            Auth::logout();
        }

        return redirect()->route('user_login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile() {
        Session::put('menu', 'user_profile');
        $user        = Auth::user();
        $client_ids  = array_column($this->userClientRelationRepository->finds(['user_id' => $user->id], ['client_id'])->toArray(), 'client_id');
        $client_apps = Client::whereIn('id', $client_ids)->where('del_flg', '!=', 1)->get();

        return view('users.profile', [
            'user'        => $user,
            'client_apps' => $client_apps
        ]);
    }
}
