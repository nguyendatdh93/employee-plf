<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\CheckIpRange;
use App\Models\Admin;
use App\Models\OauthClient;
use App\Repositories\Contracts\OauthClientRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use App\Services\MailService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

class AdminController extends Controller
{
    protected $userRepository;
    protected $userClientRelationRepository;
    protected $oauthClientRepository;
    protected $mailService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserClientRelationRepositoryInterface $userClientRelationRepository,
        OauthClientRepositoryInterface $oauthClientRepository,
        MailService $mailService
    ){
        $this->middleware(CheckIpRange::class);
        $this->middleware(AuthAdmin::class);

        $this->userRepository               = $userRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
        $this->oauthClientRepository        = $oauthClientRepository;

        $this->mailService = $mailService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUserManagerment()
    {
        Session::put('menu', 'user_management');
        $new_user_expired_hours    = Config::get('base.new_user_expired_hours');
        $new_user_expired_datetime = date('Y-m-d H:i:s',  strtotime("-$new_user_expired_hours hours" ));
        $list_users                = $this->userRepository->all();
        foreach ($list_users as $key => $user) {
            $list_users[$key]->client_apps = $this->userRepository->getClientAppsByUserId($user->id);

            if ($user->reset_password_flg != User::RESET_PASSWORD_YES && $user->updated_at <= $new_user_expired_datetime)
            {
                $list_users[$key]->is_expired = true;
            }
        }

        return view('admins.user_management', ['list_users' => $list_users]);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeUser(Request $request, $user_id = null, $user_email = null)
    {
        if (empty($user_id)) {
            return redirect()->route('404');
        }

        if (empty($user_email)) {
            return redirect()->route('404');
        }

        $result = $this->userRepository->delete($user_id);

        if (empty($result)) {
            return redirect()->route('user_management')->with('error' ,strtr(__('user_management.message_remove_user_not_success'), [':user_email' => $user_email]));
        }

        return redirect()->route('user_management')->withSuccess(strtr(__('user_management.message_remove_user_success'), [':user_email' => $user_email]));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addUserForm() {
        $client_apps = $this->oauthClientRepository->all();

        return view('admins.add_user', ['client_apps' => $client_apps]);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function addUser(Request $request) {
        $input = $request->all();

        try {
            $data = [
                'name'  => $input['name'],
                'email' => $input['email'],
            ];
            $rules = [
                'name'  => strtr('required|string|max::name_max', [':name_max' => User::NAME_MAX_LIMIT]),
                'email' => strtr('required|string|email|max::email_max', [':email_max' => User::EMAIL_MAX_LIMIT]),
            ];

            $validator = Validator::make($data, $rules);

            $validator->setAttributeNames([
                'name'  => __('add_user.form_label_name'),
                'email' => __('add_user.form_label_email')
            ]);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return back()
                    ->with('errors', $errors)
                    ->withInput();
            }

            $domain_name = substr(strrchr($input['email'], "@"), 1);
            if ($domain_name != Config::get('base.domain')) {
                return back()->withErrors(['email' => strtr(__('add_user.domain_requirement'), [':domain' => Config::get('base.domain')])])->withInput();
            }
            $password     = Config::get('base.default_password');
            $existed_user = $this->userRepository->findAllByEmail($input['email']);

            if ($existed_user && $existed_user->del_flg == User::DELETE_FLG) {
                $this->userRepository->enableUser($existed_user->id);
                $this->userClientRelationRepository->removeByUserId($existed_user->id);
                $user = $existed_user;
            } elseif ($existed_user) {
                return back()->withErrors(['email' => __('add_user.duplicate_email')])->withInput();
            }

            $client_app_ids = [];
            $client_apps    = $this->oauthClientRepository->all();
            foreach ($client_apps as $client_app) {
                $client_app_ids[] = $client_app->id;
            }

            if (!empty($input['client_apps'])) {
                foreach ($input['client_apps'] as $client_app) {
                    if (!in_array($client_app, $client_app_ids)) {
                        return back()->withErrors(['messages' => __('add_user.message_hacking')])->withInput();
                    }
                }
            }

            if (!$existed_user) {
                $user     = $this->userRepository->create([
                    'name'               => $input['name'],
                    'email'              => $input['email'],
                    'reset_password_flg' => User::RESET_PASSWORD_NO,
                    'password'           => Hash::make($password)
                ]);
            }

            if (!empty($input['client_apps'])) {
                foreach ($input['client_apps'] as $client_app) {
                    $this->userClientRelationRepository->create([
                        'user_id'   => $user->id,
                        'client_id' => $client_app
                    ]);
                }
            }

            $this->mailService->notifyNewAccount($user, $password);

            return redirect()->route('user_management')->withSuccess(strtr(__('user_management.message_add_user_success'), [':user_name' => $user->name]));
        } catch (\Exception $e) {
            return back()->withErrors(['messages' => 'ERROR: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editUserForm($id = null) {
        if (empty($id)) {
            return redirect()->route('add_user_form');
        }

        $user = $this->userRepository->find($id);
        if (empty($user)) {
            return redirect()->route('404');
        }

        $client_apps = $this->oauthClientRepository->all();
        $client_ids  = array_column($this->userClientRelationRepository->finds(['user_id' => $user->id], ['client_id'])->toArray(), 'client_id');

        return view('admins.edit_user', [
            'client_apps' => $client_apps,
            'client_ids'  => $client_ids,
            'user'        => $user
        ]);
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function editUser(Request $request) {
        $input = $request->all();

        if (empty($input['user_id'])) {
            return redirect()->route('404');
        }

        $user = $this->userRepository->find($input['user_id']);
        if (empty($user)) {
            return redirect()->route('404');
        }

        try {
            $client_ids  = [];
            $all_client_apps = $this->oauthClientRepository->all();
            foreach ($all_client_apps as $client_app) {
                $client_ids[] = $client_app->id;
            }

            $input['client_apps'] = $input['client_apps'] ?? [];
            if (!empty($input['client_apps'])) {
                foreach ($input['client_apps'] as $client_app) {
                    if (!in_array($client_app, $client_ids)) {
                        return back()->withErrors(['messages' => __('add_user.message_hacking')])->withInput();
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

            return redirect()->route('user_management')->withSuccess(strtr(__('user_management.message_update_user_success'), [':user_name' => $user->email]));
        } catch (\Exception $e) {
            return back()->withErrors(['messages' => 'ERROR: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showClientAppSetting()
    {
        Session::put('menu', 'app_setting');
        $oauth_clients            = $this->oauthClientRepository->all();
        foreach ($oauth_clients as $key => $oauth_client) {
            $oauth_client->ip_secure = explode(',', $oauth_client->ip_secure);
            $oauth_clients[$key]     = $oauth_client;
        }

        return view('admins.client_app_setting', ['oauth_clients' => $oauth_clients]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createClientAppForm()
    {
        return view('admins.create_client_app');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function createClientApp(Request $request)
    {
        try {
            $inputs = $request->all();

            $data = [
                'client_name'  => $inputs['client_name'],
                'url_redirect' => $inputs['url_redirect'],
            ];

            $rules = [
                'client_name'  => strtr('required|string|max::name_max', [':name_max' => OauthClient::CLIENT_NAME_MAX_LIMIT]),
                'url_redirect' => strtr('required|string|max::url_redirect_max', [':url_redirect_max' => OauthClient::URL_REDIRECT_MAX_LIMIT]),
            ];

            $validator = Validator::make($data, $rules);

            $validator->setAttributeNames([
                'client_name'  => __('create_client_app.client_name'),
                'url_redirect' => __('create_client_app.client_call_back')
            ]);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return back()
                    ->with('errors', $errors)
                    ->withInput();
            }

            if (filter_var($inputs['url_redirect'], FILTER_VALIDATE_URL) === FALSE) {
                return back()->withErrors(['url_redirect' => __('create_client_app.error_url_redirect_not_url')])->withInput();
            }

            if ($inputs['ip_secure'] != '') {
                $ips = explode(',', $inputs['ip_secure']);
                foreach ($ips as $ip) {
                    $is_ip = preg_match("/^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}$|^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\/[\d]{1,2}$/", $ip, $output_array);
                    if (!$is_ip) {
                        return back()->withErrors(['ip_secure' => __('create_client_app.error_ip_secure_is_ip')])->withInput();
                    }
                }
            }

            $this->oauthClientRepository->create([
                'user_id'                => 0,
                'name'                   => $inputs['client_name'],
                'secret'                 => str_random(40),
                'ip_secure'              => $inputs['ip_secure'],
                'redirect'               => $inputs['url_redirect'],
                'personal_access_client' => 0,
                'password_client'        => 0,
                'revoked'                => 0,
            ]);

            return redirect()->route('client_app_setting')->with('success', __('create_client_app.message_create_app_success'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $client_app_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeClientApp(Request $request, $client_app_id = null)
    {
        try {
            if ($client_app_id)
            {
                $this->oauthClientRepository->delete($client_app_id);

                return back()->with('success', __('client_app_setting.message_remove_success'));
            }

            return back()->with('error', __('client_app_setting.message_remove_not_success'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * @param Request $request
     * @param $client_app_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editClientAppForm(Request $request, $client_app_id = null)
    {
        if (empty($client_app_id)) {
            return redirect()->route('404');
        }

        $oauth_client = $this->oauthClientRepository->find(['id'=> $client_app_id]);
        if (count($oauth_client) == 0) {
            return redirect()->route('404');
        }

        return view('admins.edit_client_app', ['oauth_client' => $oauth_client]);
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function editClientApp(Request $request)
    {
        try {
            $inputs = $request->all();
            $data = [
                'client_name'  => $inputs['client_name'],
                'url_redirect' => $inputs['url_redirect'],
            ];

            $rules = [
                'client_name'  => strtr('required|string|max::name_max', [':name_max' => OauthClient::CLIENT_NAME_MAX_LIMIT]),
                'url_redirect' => strtr('required|string|max::url_redirect_max', [':url_redirect_max' => OauthClient::URL_REDIRECT_MAX_LIMIT]),
            ];

            $validator = Validator::make($data, $rules);

            $validator->setAttributeNames([
                'client_name'  => __('edit_client_app.client_name'),
                'url_redirect' => __('edit_client_app.client_call_back')
            ]);

            if ($validator->fails()) {
                $errors = $validator->messages();

                return back()
                    ->with('errors', $errors)
                    ->withInput();
            }

            if (filter_var($inputs['url_redirect'], FILTER_VALIDATE_URL) === FALSE) {
                return back()->withErrors(['url_redirect' => __('create_client_app.error_url_redirect_not_url')])->withInput();
            }

            if ($inputs['ip_secure'] != '') {
                $ips = explode(',', $inputs['ip_secure']);
                foreach ($ips as $ip) {
                    $is_ip = preg_match("/^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}$|^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\/[\d]{1,2}$/", $ip, $output_array);
                    if (!$is_ip) {
                        return back()->withErrors(['ip_secure' => __('create_client_app.error_ip_secure_is_ip')])->withInput();
                    }
                }
            }

            $this->oauthClientRepository->update([
                'name'      => $request->get('client_name'),
                'redirect'  => $request->get('url_redirect'),
                'ip_secure' => $request->get('ip_secure'),
            ], $request->get('client_id'));

            return redirect()->route('client_app_setting')->with('success', __('edit_client_app.message_edit_client_app'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetExpiredUser($id = null) {
        if (empty($id)) {
            return redirect()->route('404');
        }

        try {
            $user = $this->userRepository->findBy(['id' => $id, 'reset_password_flg:<>' => User::RESET_PASSWORD_YES ]);
            if (empty($user)) {
                return redirect()->route('404');
            }

            $this->userRepository->update([
                'reset_password_flg' => User::RESET_PASSWORD_EXTEND
            ], $user->id);

            $this->mailService->notifyResetExpireTime($user);

            return redirect()->route('user_management')->withSuccess(strtr(__('reset_expire_password.expire_time'), [':user_name' => $user->name]));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showGetSqlAddAdminForm(Request $request)
    {
        $sql = "INSERT INTO employee.admins (name, email, password, del_flg) VALUES ('".$request->get('name')."', '".$request->get('email')."', '".Hash::make($request->get('password'))."', '0')";

        return view('admins.add_admin', ['sql' => $sql]);
    }
}
