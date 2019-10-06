<?php

namespace Odin\utils;

class FlashMessages
{

    private static $previous;
    private static $key = "_odinFlashMessages";
    private static $storage;
    
    public function __construct()
    {
        self::init();
    }

    public static function init()
    {
        @session_start();
        self::$storage = &$_SESSION;

        if(isset(self::$storage[self::$key]) && is_array(self::$storage[self::$key])){
            self::$previous = self::$storage[self::$key];
        }

        self::$storage[self::$key] = [];
    }

    public static function add($key, $value)
    {
        self::init();
        if(!isset(self::$storage[self::$key][$key])){
            self::$storage[self::$key][$key] = $value;
        }
    }

    public static function get($key)
    {
        if(self::has($key)){
            return self::$previous[$key];
        }
        return null;
    }

    public static function has($key)
    {
        return isset(self::$previous[$key]);
    }
}
