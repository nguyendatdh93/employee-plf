<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIpRange;
use App\Repositories\Eloquents\OauthClientRepository;
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

    use AuthenticatesUsers {
        AuthenticatesUsers::showLoginForm as parentShowLoginForm;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * @var OauthClientRepository
     */
    private $oauthClientRepository;

    /**
     * Create a new controller instance.
     *
     * @param OauthClientRepository $oauthClientRepository
     */
    public function __construct(
        OauthClientRepository $oauthClientRepository
    ){
        $this->middleware(CheckIpRange::class);
        $this->middleware('guest')->except('logout');
        $this->oauthClientRepository = $oauthClientRepository;
    }

    public function showLoginForm()
    {
        $oauth_client = [];
        if(session()->get('url.intended')) {
            parse_str( parse_url( session()->get('url.intended'), PHP_URL_QUERY), $url);
            if (!empty($url['client_id']) && !empty($url['client_secret'])) {
                $oauth_client = $this->oauthClientRepository->findBy(['id' => $url['client_id'], 'secret' => $url['client_secret']]);
            }
        }

        return view('auth.login', ['oauth_client' => $oauth_client]);
    }
}
