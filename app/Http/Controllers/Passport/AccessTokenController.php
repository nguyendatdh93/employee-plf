<?php

namespace App\Http\Controllers\Passport;

use App\Repositories\Contracts\OauthClientRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\AuthorizationServer;

class AccessTokenController
{
    use HandlesOAuthErrors;

    /**
     * The authorization server.
     *
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;

    /**
     * The token repository instance.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokens;

    /**
     * The JWT parser instance.
     *
     * @var \Lcobucci\JWT\Parser
     */
    protected $jwt;
    /**
     * @var AuthService
     */
    protected $authService;
    /**
     * @var OauthClientRepositoryInterface
     */
    protected $oauthClientRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer $server
     * @param  \Laravel\Passport\TokenRepository $tokens
     * @param  \Lcobucci\JWT\Parser $jwt
     * @param AuthService $authService
     * @param OauthClientRepositoryInterface $oauthClientRepository
     */
    public function __construct(AuthorizationServer $server,
                                TokenRepository $tokens,
                                JwtParser $jwt,
                                AuthService $authService,
                                OauthClientRepositoryInterface $oauthClientRepository
    )
    {
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
        $this->authService = $authService;
        $this->oauthClientRepository = $oauthClientRepository;
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Illuminate\Http\Response
     */
    public function issueToken(ServerRequestInterface $request, Request $httpRequest)
    {
        $oauth_client = $this->checkOauthClientApp($httpRequest);
        if (!$oauth_client) {
            return json_encode(['code' => 401, 'state' => 'error_unauthorized']);
        }

        if (!$this->checkIpThirdParty($httpRequest, $oauth_client)) {
            return json_encode(['code' => 403, 'state' => 'error_ip']);
        }

        return $this->withErrorHandling(function () use ($request) {
            return $this->convertResponse(
                $this->server->respondToAccessTokenRequest($request, new Psr7Response)
            );
        });
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

        if ($this->checkIpRange(explode(',', $oauth_client->ip_secure), $request->ip())) {
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
        if ($this->authService->checkIpRange($ip_secure, $ip)) {
            return true;
        }

        return false;
    }
}