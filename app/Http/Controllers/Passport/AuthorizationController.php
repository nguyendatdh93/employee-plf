<?php

namespace App\Http\Controllers\Passport;

use App\Models\Log;
use App\Models\OauthClient;
use App\Models\Slack;
use App\Notifications\SlackNotification;
use App\Repositories\Contracts\LogRepositoryInterface;
use App\Repositories\Eloquents\OauthClientRepository;
use App\Repositories\Eloquents\UserClientRelationRepository;
use App\Services\AuthService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\User;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Contracts\Routing\ResponseFactory;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Laravel\Passport\Http\Controllers\RetrievesAuthRequestFromSession;
use Response;

class AuthorizationController extends \Laravel\Passport\Http\Controllers\AuthorizationController
{
    use HandlesOAuthErrors, RetrievesAuthRequestFromSession;

    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected $server;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    protected $userClientRelationRepository;
    protected $oauthClientRepository;

    /**
     * @var LogRepositoryInterface
     */
    protected $logRepository;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * Create a new controller instance.
     *
     * @param  AuthorizationServer $server
     * @param  ResponseFactory $response
     * @param OauthClientRepository $oauthClientRepository
     * @param UserClientRelationRepository $userClientRelationRepository
     * @param LogRepositoryInterface $logRepository
     * @param AuthService $authService
     */
    public function __construct(
        AuthorizationServer $server,
        ResponseFactory $response,
        OauthClientRepository $oauthClientRepository,
        UserClientRelationRepository $userClientRelationRepository,
        LogRepositoryInterface $logRepository,
        AuthService $authService
    ) {
        $this->server                       = $server;
        $this->response                     = $response;
        $this->oauthClientRepository        = $oauthClientRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
        $this->logRepository                = $logRepository;
        $this->authService                  = $authService;
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  ServerRequestInterface $psrRequest
     * @param  Request $request
     * @param  ClientRepository $clients
     * @param TokenRepository $tokens
     * @return Response
     */
    public function authorize(
        ServerRequestInterface $psrRequest,
        Request $request,
        ClientRepository $clients,
        TokenRepository $tokens
    ) {;
        $validator = Validator::make($request->all(),[
            'client_id'     => 'required',
            'client_secret' => 'required',
            'redirect_uri'  => 'required',
            'response_type' => 'required',
        ]);

        if ($validator->fails()) {
            Notification::send(new Slack(), new SlackNotification(
                'Error ' . OauthClient::HTTP_CODE_UNAUTHORIZED, 'Exception : Client App validation error', $validator->messages()->first()));
            return redirect($request->get('redirect_uri').'?httpcode='.OauthClient::HTTP_CODE_UNAUTHORIZED.'&state='. OauthClient::ERROR_UNAUTHORIZED);
        }

        $oauth_client = $this->checkOauthClientApp($request);
        if (!$oauth_client) {
            Notification::send(new Slack(), new SlackNotification(
                'Error ' . OauthClient::HTTP_CODE_UNAUTHORIZED, 'Exception : Client app is not exits!', 'Client id = ' . $request->get('client_id') . "\n Client secret = " . $request->get('client_secret')));
            return redirect($request->get('redirect_uri').'?httpcode='.OauthClient::HTTP_CODE_UNAUTHORIZED.'&state='. OauthClient::ERROR_UNAUTHORIZED);
        }

        $user_client_relation = $this->checkPermissionUseApp($request);
        if (!$user_client_relation) {
            Notification::send(new Slack(), new SlackNotification(
                'Error ' . OauthClient::HTTP_CODE_FORBIDDEN, 'Exception : User does not have permission to use app', 'User email = ' . Auth::user()->email . "\n Client app = " . $oauth_client->name));
            return redirect($request->get('redirect_uri').'?httpcode='.OauthClient::HTTP_CODE_FORBIDDEN.'&state='. OauthClient::ERROR_PERMISSION);
        }

        $change_password = $this->authService->checkResetPassword();
        if ($change_password == AuthService::NO_CHANGE_PASSWORD) {
            Session::put('third_party_login', $request->getRequestUri());

            return redirect()->route('reset_password');
        } elseif ($change_password == AuthService::EXPIRED_PASSWORD) {
            return redirect()->route('expired_login');
        }

        $response = $this->withErrorHandling(function () use ($psrRequest, $request, $clients, $tokens) {
            $authRequest = $this->server->validateAuthorizationRequest($psrRequest);

            $scopes = $this->parseScopes($authRequest);
            $token  = $tokens->findValidToken(
                $user   = $request->user(),
                $client = $clients->find($authRequest->getClient()->getIdentifier())
            );

            if ($token && $token->scopes === collect($scopes)->pluck('id')->all()) {
                return $this->approveRequest($authRequest, $user);
            }

            $request->session()->put('authRequest', $authRequest);

            $authRequest = $this->getAuthRequestFromSession($request);

            return $this->server->completeAuthorizationRequest(
                $authRequest, new Psr7Response
            );
        });

        if ($response->getStatusCode() == OauthClient::HTTP_CODE_UNAUTHORIZED) {
            Notification::send(new Slack(), new SlackNotification(
                'Error ' . OauthClient::HTTP_CODE_UNAUTHORIZED, 'Exception : User can not access to app', 'User email = ' . Auth::user()->email . "\n Client app = " . $oauth_client->name));
            return redirect($request->get('redirect_uri').'?httpcode='.OauthClient::HTTP_CODE_UNAUTHORIZED.'&state='. OauthClient::ERROR_UNAUTHORIZED);
        }

        $this->saveLog($user_client_relation->id, $request->ip());

        return $response;
    }

    /**
     * @param $request
     * @return bool|mixed
     */
    private function checkOauthClientApp($request)
    {
        $oauth_client = $this->oauthClientRepository->findBy(['id'  =>$request->get('client_id'), 'secret' => $request->get('client_secret')]);
        if (empty($oauth_client)) {
            return false;
        }

        return $oauth_client;
    }

    /**
     * @param $request
     * @return bool
     */
    private function checkPermissionUseApp($request)
    {
        $user_client_relation = $this->userClientRelationRepository->findBy(['client_id' => $request->get('client_id'), 'user_id' => Auth::id()]);
        if ($user_client_relation) {
            return $user_client_relation;
        }

        return false;
    }

    /**
     * @param $user_client_id
     * @param $ip
     */
    private function saveLog($user_client_id, $ip)
    {
        if (!empty($user_client_id) && !empty($ip))
        {
            $this->logRepository->create([
                'user_client_id' => $user_client_id,
                'ip'             => $ip
            ]);
        }
    }
}
