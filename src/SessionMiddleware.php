<?php

namespace Slim\Session;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\SessionInterface;

class SessionMiddleware
{
    /**
     * @var \Slim\Session\SessionInterface
     */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        $this->session->start();
        $response = $next($request, $response);
        $this->session->save();

        return $response;
    }
}