<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\CheckIpRange;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $userRepository;
    protected $userClientRelationRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserClientRelationRepositoryInterface $userClientRelationRepository
    ){
        $this->middleware(CheckIpRange::class);
        $this->middleware(AuthAdmin::class);

        $this->userRepository = $userRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
    }

    public function showUserManagerment()
    {
        $list_users = $this->userRepository->all();
        foreach ($list_users as $key => $user) {
            $list_users[$key]->client_apps = $this->userRepository->getClientAppsByUserId($user->id);
        }

        return view('admins.user_managerment', ['list_users' => $list_users]);
    }

    public function removeUser(Request $request, $user_id)
    {
        $this->userRepository->removeUser($user_id);

        return redirect()->route('user-managerment');
    }

    public function addUserForm() {
        $client_apps = Client::all();

        return view('admins.add_user', ['client_apps' => $client_apps]);
    }

    public function addUser(Request $request) {
        $input = $request->all();

        try {
            $data = [
                'name'  => $input['name'],
                'email' => $input['email'],
            ];
            $rules = [
                'name'  => strtr('required|string|max::name_max', [':name_max' => User::NAME_MAX_LIMIT]),
                'email' => strtr('required|string|email|max::email_max|unique:users', [':email_max' => User::EMAIL_MAX_LIMIT]),
            ];

            /** @var Validator $validator */
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $errors = $validator->messages();

                return back()
                    ->with('errors', $errors)
                    ->withInput();
            }

            $client_app_ids = [];
            $client_apps    = Client::all();
            foreach ($client_apps as $client_app) {
                $client_app_ids[] = $client_app->id;
            }

            if (!empty($input['client_apps'])) {
                foreach ($input['client_apps'] as $client_app) {
                    if (!in_array($client_app, $client_app_ids)) {
                        return back()->withErrors(['messages' => "Are you hacking? Don't have the client app you post!"])->withInput();
                    }
                }
            }

            $user = $this->userRepository->create([
                'name'               => $input['name'],
                'email'              => $input['email'],
                'reset_password_flg' => User::RESET_PASSWORD_NO,
                'password'           => Hash::make(Config::get('base.default_password'))
            ]);

            if (!empty($input['client_apps'])) {
                foreach ($input['client_apps'] as $client_app) {
                    $this->userClientRelationRepository->create([
                        'user_id'   => $user->id,
                        'client_id' => $client_app
                    ]);
                }
            }

            return redirect()->route('user-managerment')->withSuccess(strtr(':user_name is added successful!', [':user_name' => $user->name]));
        } catch (\Exception $e) {
            return back()->withErrors(['messages' => 'ERROR: ' . $e->getMessage()])->withInput();
        }
    }

    public function editUserForm($id = null) {
        if (empty($id)) {
            return redirect()->route('add-user-form');
        }

        $user = $this->userRepository->find($id);
        if (empty($user)) {
            die('404');
        }

        $client_apps = [
            'app 1', 'app 2', 'app 3'
        ];

        return view('admins.edit_user', [
            'client_apps' => $client_apps,
            'user'        => $user
        ]);
    }

    public function editUser() {
        return redirect()->route('user-managerment');
    }
}
