<?php

namespace App\Security\Firewall;

use App\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class WsseListener
{
    protected $tokenStorage;
    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $wsseRegex = '/UsernameToken Username="(?P<username>[^"]+)", PasswordDigest="(?P<digest>[^"]+)", Created="(?P<created>[^"]+)", Nonce="(?P<nonce>[a-zA-Z0-9+\/]+={0,2})"/';
        if (!$request->headers->has('x-wsse') || 1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
            return;
        }

        $token = new WsseUserToken();
        $token->setUser($matches['username']);

        $token->digest  = $matches['digest'];
        $token->nonce   = $matches['nonce'];
        $token->created = $matches['created'];

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            $token = $this->tokenStorage->getToken();
            if ($token instanceof WsseUserToken) {
                 $this->tokenStorage->setToken(null);
            }
        }

        // deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
