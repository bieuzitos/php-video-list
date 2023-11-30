<?php

namespace Source\Support;

/**
 * Class Session
 * 
 * @package Source\Support
 */
class Session
{
    /**
     * @return void
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function __get(string $key): string|null
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * 
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * @param string $key
     * 
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @return object|null
     */
    public function all(): object|null
    {
        return (object)$_SESSION;
    }

    /**
     * @param string $key
     * @param mixed $value
     * 
     * @return Session
     */
    public function set(string $key, mixed $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object)$value : $value);

        return $this;
    }

    /**
     * @param string $key
     * 
     * @return Session
     */
    public function unset(string $key): Session
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }

        return $this;
    }

    /**
     * @return Session
     */
    public function regenerate(): Session
    {
        session_regenerate_id(true);

        return $this;
    }

    /**
     * @return Session
     */
    public function destroy(): Session
    {
        session_destroy();

        return $this;
    }
}
