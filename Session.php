<?php

namespace app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    /**
     * Session constructor.
     * 
     * @return void 
     */
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            // mark to be removed
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    /**
     * Sets a flash message and marks the message to be removed on 
     * next request.
     * 
     * @param string $key Message key
     * @param string $message Text of the message
     * @return void 
     */
    public function setFlash(string $key, string $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    /**
     * Returns the flash message for the given key, if one exists.
     * 
     * @param string $key 
     * @return mixed 
     */
    function getFlash(string $key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    /**
     * Sets a given key => value pair into the session.
     * 
     * @param string $key 
     * @param mixed $value 
     * @return void 
     */
    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Returns the current session for the given key.
     * 
     * @param string $key 
     * @return mixed 
     */
    public function get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * Unsets the current session for the given key.
     * 
     * @param string $key 
     * @return void 
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Unsets all flash messages marked to be removed during the constructor.
     * 
     * @return void 
     */
    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
