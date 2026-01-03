<?php

namespace core;

/**
 * Session class
 * 
 * This class is used to handle sessions.
 * Inspired by the Session class from cakephp.
 * 
 * Basic methods are:
 * - init(): Start the session
 * - set($key, $value): Set a session variable
 * - get($key): Get a session variable
 * - remove($key): Remove a session variable
 * - destroy(): Destroy the session
 * - has($key): Check if a session variable exists
 * 
 * Flash messages:
 * Flash messages are used to display messages to the user. So for example, if a user
 * successfully registered, we can set a flash message to display a success message.
 * 
 * Flash messages methods:
 * - setFlash($key, $message): Set a flash message
 * - getFlash($key): Get a flash message
 * - hasFlash($key): Check if a flash message exists
 */
class Session
{
    public static function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }


    //Flash messages
    public static function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function hasFlash($key)
    {
        return isset($_SESSION['flash'][$key]);
    }
}
