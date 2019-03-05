<?php

namespace Odin\routes;

trait AppContext
{
    protected static $app;

    public static function instance($appInstance)
    {
        self::$app = $appInstance;
    }

    public static function getInstance()
    {
        return self::$app;
    }
}
