<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/codeguy/Slim
 * @copyright Copyright (c) 2011-2015 Josh Lockhart
 * @license   https://github.com/codeguy/Slim/blob/master/LICENSE (MIT License)
 */
namespace Slim\Session;

use SessionHandlerInterface;
use Slim\Collection;

/**
 * Session
 *
 * This class provides a wrapper around a native PHP session. It will
 * start a new PHP session on-demand if a PHP session is not already
 * started. If a session is already started, this class will use the existing
 * session; however, it is recommended that you allow this class to start
 * and configure the PHP session on its own.
 *
 * This class will use the default filesystem session storage. You may
 * define your own session handler to persist session data elsewhere.
 *
 * Session data is serialized and placed in the `slim.session` namespace
 * to avoid polluting the global session namespace potentially used
 * by third-party code.
 */
class NativeSession implements SessionStorageInterface
{
    protected $namespace;

    protected $session;

    /**
     * Reference to custom session handler
     *
     * @var SessionHandlerInterface
     */
    protected $handler;

    /**
     * Create new session
     *
     * By default, this class assumes the use of the native file system session handler
     * for persisting session data. You can, however, inject a custom session handler
     * with this constructor method.
     *
     * @param null|SessionHandlerInterface $handler
     */
    public function __construct(SessionHandlerInterface $handler = null, $namespace = 'slim.session')
    {
        if ($handler !== null) {
            session_set_save_handler(
              array($handler, 'open'),
              array($handler, 'close'),
              array($handler, 'read'),
              array($handler, 'write'),
              array($handler, 'destroy'),
              array($handler, 'gc')
            );
        }
        $this->handler = $handler;
        $this->namespace = $namespace;
    }

    /**
     * Start the session
     */
    public function start()
    {
        // Initialize new session if a session is not already started
        if ($this->isStarted() === false) {
            $this->initialize();
        }

        $this->session = $_SESSION[$this->namespace];
    }

    public function save()
    {
        session_write_close();
        return true;
    }

    /**
     * Is session started?
     *
     * @return bool
     * @link   http://us2.php.net/manual/function.session-status.php#113468
     */
    public function isStarted()
    {
        return session_status() === PHP_SESSION_ACTIVE ? true : false;
    }

    /**
     * Initialize new session
     *
     * @throws \RuntimeException If session cannot start
     */
    public function initialize()
    {
        // Disable PHP cache headers
        session_cache_limiter('');

        // Ensure session ID uses valid characters when stored in HTTP cookie
        if (ini_get('session.use_cookies') == true) {
            ini_set('session.hash_bits_per_character', 5);
        }

        // Start session
        if (session_start() === false) {
            throw new \RuntimeException('Cannot start session. Unknown error while invoking `session_start()`.');
        };
    }

    public function getId()
    {
        return session_id();
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->session[$key] : $default;
    }

    public function set($key, $val)
    {
        $this->session[$key] = $val;
    }

    public function has($key)
    {
        return isset($this->session[$key]);
    }

    public function regenerate($deleteOldSession = false)
    {
        return session_regenerate_id($deleteOldSession);
    }

    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->session[$key]);
        }
    }

    public function destroy()
    {
        session_destroy();
    }
}