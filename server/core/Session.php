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
    /**
     * Start the session
     * 
     * We check if the session is already started. If not, we start it.
     * This makes sure we don't get those annoying "session already started" errors.
     * 
     * @return void
     */
    public static function init()
    {
        // We check if is eqal to PHP_SESSION_NONE.
        // This means that the session is not started.
        // https://www.php.net/manual/en/function.session-status.php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable
     * 
     * Just a wrapper around $_SESSION.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        // We set the session variable with the key and the value.
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable
     * 
     * Safely retrives a value. Returns null if key doesn't exist.
     * 
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Remove a session variable
     * 
     * Unsets a specific key from the session.
     * 
     * @param string $key
     * @return void
     */
    public static function remove($key)
    {
        if (isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the session
     * 
     * Wipes everything clean. Used for logout.
     * 
     * @return void
     */
    public static function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Check if a session variable exists
     * 
     * Useful for checking if user is logged in, etc.
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }


    //Flash messages
    /**
     * Set a flash message
     * 
     * Stores a message to be displayed on the next request.
     * Great for "User saved!" notifications.
     * 
     * @param string $key
     * @param string $message
     * @return void
     */
    public static function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Get a flash message
     * 
     * Retrieves and DELETES the message.
     * This is the "flash" part - it only lasts for one read.
     * 
     * @param string $key
     * @return string|null
     */
    public static function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    /**
     * Check if a flash message exists
     * 
     * @param string $key
     * @return bool
     */
    public static function hasFlash($key)
    {
        return isset($_SESSION['flash'][$key]);
    }
}
