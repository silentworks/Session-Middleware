<?php

namespace Slim\Session;

use Pimple\ServiceProviderInterface;

class SessionServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['session'] = function ($container) {
            return new Session($container['session.storage']);
        };

        $container['session.storage'] = function ($container)
        {
            return new NativeSession($container['session.storage.handler']);
        }

        $container['session.storage.handler'] = null;
    }
}