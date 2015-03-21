<?php

namespace Slim\Session;

class Session
{
    protected $storage;

    public function __construct(SessionStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function get($name, $default = null)
    {
        return $this->storage->get($name, $default);
    }

    public function has($name)
    {
        return $this->storage->has($name);
    }

    public function set($name, $value)
    {
        $this->storage->set($name, $value);
    }

    public function remove($name)
    {
        $this->storage->remove($name);
    }

    public function destroy()
    {
        $this->storage->destroy();
    }

    public function isStarted()
    {
        return $this->storage->isStarted();
    }
}