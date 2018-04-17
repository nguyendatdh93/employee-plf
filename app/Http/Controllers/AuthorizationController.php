<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquents\OauthClientRepository;
use App\Repositories\Eloquents\UserClientRelationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

class AuthorizationController
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
     * Create a new controller instance.
     *
     * @param  AuthorizationServer $server
     * @param  ResponseFactory $response
     * @param OauthClientRepository $oauthClientRepository
     * @param UserClientRelationRepository $userClientRelationRepository
     */
    public function __construct(
        AuthorizationServer $server,
        ResponseFactory $response,
        OauthClientRepository $oauthClientRepository,
        UserClientRelationRepository $userClientRelationRepository
    ) {
        $this->server                       = $server;
        $this->response                     = $response;
        $this->oauthClientRepository        = $oauthClientRepository;
        $this->userClientRelationRepository = $userClientRelationRepository;
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
    ) {
        if (!$this->checkOauthClientApp($request)) {
            return redirect($request->get('redirect_uri').'?code=401&state=error_unauthorized');
        }

        $oauth_client = $this->checkOauthClientApp($request);

        if (!$this->checkIpThirdParty($request, $oauth_client)) {
            return redirect($request->get('redirect_uri').'?code=403&state=error_ip');
        }

        if (!$this->checkPermissionUseApp($request)) {
            return redirect($request->get('redirect_uri').'?code=403&state=error_permission');
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

        if ($response->getStatusCode() == 401) {
            return redirect($request->get('redirect_uri').'?code=401&state=error_unauthorized');
        }

        return $response;
    }

    /**
     * Transform the authorization requests's scopes into Scope instances.
     *
     * @param  AuthRequest  $request
     * @return array
     */
    protected function parseScopes($authRequest)
    {
        return Passport::scopesFor(
            collect($authRequest->getScopes())->map(function ($scope) {
                return $scope->getIdentifier();
            })->all()
        );
    }

    /**
     * Approve the authorization request.
     *
     * @param  AuthorizationRequest  $authRequest
     * @param  Model  $user
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function approveRequest($authRequest, $user)
    {
        $authRequest->setUser(new User($user->getKey()));

        $authRequest->setAuthorizationApproved(true);

        return $this->server->completeAuthorizationRequest(
            $authRequest, new Psr7Response
        );
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
     * @param $oauth_client
     * @return bool
     */
    private function checkIpThirdParty($request, $oauth_client)
    {
        if ($oauth_client->ip_secure == '') {
            return true;
        }

        if ($this->checkIpRange(explode(',', $oauth_client->ip_secure), $request->get('ip'))) {
            return true;
        }

        return false;
    }

    /**
     * @param $ip_secure
     * @param $ip
     * @return bool
     */
    private function checkIpRange($ip_secure, $ip)
    {
        foreach ($ip_secure as $range) {
            if (strpos($range, '/') == false) {
                $range .= '/32';
            }
            // $range is in IP/CIDR format eg 127.0.0.1/24
            list($range, $netmask) = explode('/', $range, 2);
            $ip_decimal       = ip2long($ip);
            $range_decimal    = ip2long($range);
            $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
            $netmask_decimal  = ~ $wildcard_decimal;
            if (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $request
     * @return bool
     */
    private function checkPermissionUseApp($request)
    {
        $user_client_relation = $this->userClientRelationRepository->findBy(['client_id' => $request->get('client_id'), 'user_id' => Auth::id()]);
        if ($user_client_relation) {
            return true;
        }

        return false;
    }
}
