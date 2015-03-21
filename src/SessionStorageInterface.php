<?php

namespace Slim\Session;

interface SessionStorageInterface
{
    public function start();

    public function isStarted();

    public function regenerate();

    public function save();

    public function get($name, $default = '');

    public function set($name, $value);

    public function has($name);

    public function remove($name);

    public function destroy();
}