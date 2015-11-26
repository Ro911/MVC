<?php

abstract class Registry
{
    private static $instances = array();


    /**
     * @param $key
     * @param $instance
     */
    public static function set($key, $instance)
    {
        self::$instances[$key] = $instance;
    }


    /**
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return isset(self::$instances[$key]);
    }


    /**
     * @param $key
     * @return null
     */
    public static function get($key)
    {
        if (self::has($key)) {
            return self::$instances[$key];
        }
        return null;
    }
}