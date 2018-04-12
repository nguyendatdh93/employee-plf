<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\CheckIpRange;
use App\Repositories\Contracts\OauthClientRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Session;

class AdminController extends Controller
{
    protected $userRepository;
    protected $userClientRelationRepository;
    protected $oauthClientRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserClientRelationRepositoryInterface $userClientRelationRepository,
        OauthClientRepositoryInterface $oauthClientRepository
    ){
        $this->middleware(CheckIpRange::class);
        $this->middleware(AuthAdmin::class);

        $this->userRepository               = $userRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
        $this->oauthClientRepository        = $oauthClientRepository;
    }

    public function showUserManagerment()
    {
        Session::put('menu', 'user_managerment');
        $list_users = $this->userRepository->all();
        foreach ($list_users as $key => $user) {
            $list_users[$key]->client_apps = $this->userRepository->getClientAppsByUserId($user->id);
        }

        return view('admins.user_managerment', ['list_users' => $list_users]);
    }

    public function removeUser(Request $request, $user_id)
    {
        $this->userRepository->removeUser($user_id);

        return redirect()->route('user_managerment')->withSuccess(strtr('User :user_id is removed successful!', [':user_id' => $user_id]));;
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

            return redirect()->route('user_managerment')->withSuccess(strtr(':user_name is added successful!', [':user_name' => $user->name]));
        } catch (\Exception $e) {
            return back()->withErrors(['messages' => 'ERROR: ' . $e->getMessage()])->withInput();
        }
    }

    public function editUserForm($id = null) {
        if (empty($id)) {
            return redirect()->route('add_user_form');
        }

        $user = $this->userRepository->find($id);
        if (empty($user)) {
            die('404');
        }

        $client_apps = Client::all();
        $client_ids = array_column($this->userClientRelationRepository->finds(['user_id' => $user->id], ['client_id'])->toArray(), 'client_id');

        return view('admins.edit_user', [
            'client_apps' => $client_apps,
            'client_ids'  => $client_ids,
            'user'        => $user
        ]);
    }

    public function editUser(Request $request) {
        $input = $request->all();

        if (empty($input['user_id'])) {
            die('404');
        }

        $user = $this->userRepository->find($input['user_id']);
        if (empty($user)) {
            die('404');
        }

        try {
            $client_ids  = [];
            $all_client_apps = Client::all();
            foreach ($all_client_apps as $client_app) {
                $client_ids[] = $client_app->id;
            }

            $input['client_apps'] = $input['client_apps'] ?? [];
            if (!empty($input['client_apps'])) {
                foreach ($input['client_apps'] as $client_app) {
                    if (!in_array($client_app, $client_ids)) {
                        return back()->withErrors(['messages' => "Are you hacking? Don't have the client app you post!"])->withInput();
                    }
                }
            }

            foreach ($client_ids as $client_id) {
                $this->userClientRelationRepository->makeModel();
                $filter = [
                    'user_id'   => $user->id,
                    'client_id' => $client_id
                ];
                $user_client_relation = $this->userClientRelationRepository->findBy($filter);

                if (empty($user_client_relation)) {
                    if (in_array($client_id, $input['client_apps'])) {
                        $this->userClientRelationRepository->create([
                            'user_id'   => $user->id,
                            'client_id' => $client_id
                        ]);
                    } else {
                        continue;
                    }
                } else {
                    if (in_array($client_id, $input['client_apps'])) {
                        continue;
                    } else {
                        $this->userClientRelationRepository->update([
                            'del_flg'   => 1
                        ], $user_client_relation->id);
                    }
                }
            }

            return redirect()->route('user_managerment')->withSuccess(strtr(':user_name is updated successful!', [':user_name' => $user->name]));
        } catch (\Exception $e) {
            return back()->withErrors(['messages' => 'ERROR: ' . $e->getMessage()])->withInput();
        }
    }

    public function showClientAppSetting()
    {
        Session::put('menu', 'app_setting');
        $oauth_clients = $this->oauthClientRepository->all();

        return view('admins.client_app_setting', ['oauth_clients' => $oauth_clients]);
    }

    public function createClientAppForm()
    {
        return view('admins.create_client_app');
    }

    public function createClientApp(Request $request)
    {
        $inputs = $request->all();

        if ($inputs['client_name'] == '') {
            return redirect()->route('create_client_app_form')->with('error_client_name', __('create_client_app.error_client_name'));
        }

        if ($inputs['url_redirect'] == '') {
            return redirect()->route('create_client_app_form')->with('error_url_redirect', __('create_client_app.error_url_redirect'));
        }

        if (filter_var($inputs['url_redirect'], FILTER_VALIDATE_URL) === FALSE) {
            return redirect()->route('create_client_app_form')->with('error_url_redirect', __('create_client_app.error_url_redirect_not_url'));
        }

        if ($inputs['ip_secure'] == '') {
            return redirect()->route('create_client_app_form')->with('error_ip_secure', __('create_client_app.error_ip_secure'));
        }

        if (filter_var($inputs['ip_secure'], FILTER_VALIDATE_IP) === FALSE) {
            return redirect()->route('create_client_app_form')->with('error_ip_secure', __('create_client_app.error_ip_secure_is_ip'));
        }

        $this->oauthClientRepository->create([
            'user_id' => 0,
            'name' => $inputs['client_name'],
            'secret' => str_random(40),
            'ip_secure' => $inputs['ip_secure'],
            'redirect' => $inputs['url_redirect'],
            'personal_access_client' => 0,
            'password_client' => 0,
            'revoked' => 0,
        ]);

        return redirect()->route('client_app_setting')->with('message_create_client_success', __('create_client_app.message_create_app_success'));
    }

    public function removeClientApp(Request $request, $client_app_id)
    {
        if ($client_app_id)
        {
            $this->oauthClientRepository->delete($client_app_id);

            return back()->with('message_remove_client_app_success', __('client_app_setting.message_remove_success'));
        }

        return back()->with('message_remove_client_app_error', __('client_app_setting.message_remove_not_success'));
    }

    public function editClientAppForm(Request $request, $client_app_id)
    {
        $oauth_client = $this->oauthClientRepository->find(['id'=> $client_app_id]);

        return view('admins.edit_client_app', ['oauth_client' => $oauth_client]);
    }

    public function editClientApp(Request $request)
    {
        $this->oauthClientRepository->update([
            'name' => $request->get('client_name'),
            'redirect' => $request->get('url_redirect'),
            'ip_secure' => $request->get('ip_secure'),
        ], $request->get('client_id'));

        return redirect()->route('client_app_setting')->with('message_edit_client_app', __('edit_client_app.message_edit_client_app'));
    }
}
