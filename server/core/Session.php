<?php

namespace core;

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
