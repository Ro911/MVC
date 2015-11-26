<?php

abstract class Cookie
{
    public static function get($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return null;
    }

    public static function delete($key)
    {
        if (isset($_COOKIE[$key])) {
            self::set($key, '', -1);
            unset($_COOKIE[$key]);
        }
    }

    public static function set($key, $value, $time = 31536000)
    {
        setcookie($key, $value, time() + $time, '/');
    }
}