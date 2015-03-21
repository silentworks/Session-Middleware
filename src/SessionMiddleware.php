<?php

namespace Slim\Session;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\SessionInterface;

class SessionMiddleware
{
    /**
     * @var \Slim\Session\SessionStorageInterface
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

        if ($this->session && $this->session->isStarted()) {
            $this->session->save();
        }        

        return $response;
    }
}