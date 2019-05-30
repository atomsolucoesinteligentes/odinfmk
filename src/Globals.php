<?php

namespace Odin;

use Odin\routes\AppContext;

class globals
{
    use AppContext;

    protected static $container;
    protected static $keys;

    protected static function prepare()
    {
        if(empty(self::$container))
            self::$container = AppContext::getInstance()->getContainer();
    }

    protected static function define()
    {
        $asGlobal = [];
        foreach(self::$keys as $key)
        {
            $asGlobal[$key] = self::$container->{$key};
        }

        AppContext::getInstance()->render->setAsGlobal($asGlobal);
    }

    public static function set(array $globals)
    {
        self::prepare();
        foreach($globals as $key => $global)
        {
            self::$keys[] = $key;
            self::$container->{$key} = self::$container->shared(function() use ($global){
                return $global;
            });
        }
        self::define();
    }
}
