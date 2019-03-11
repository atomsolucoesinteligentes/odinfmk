<?php

namespace Odin\routes;

use Odin\App;
use Odin\routes\AppContext;
use Odin\utils\Config;

class Routes
{
    use AppContext;

    public static function init()
    {
        AppContext::instance(new App());
    }

    public static function getInstance()
    {
        return AppContext::getInstance();
    }

    public static function group($prefix, $fnc)
    {
        return AppContext::getInstance()->group($prefix, $fnc);
    }

    public static function get($pattern, $callable, array $conditions = [])
    {
        return AppContext::getInstance()->get($pattern, $callable, $conditions);
    }

    public static function post($pattern, $callable, array $conditions = [])
    {
        return AppContext::getInstance()->post($pattern, $callable, $conditions);
    }

    public static function put($pattern, $callable, array $conditions = [])
    {
        return AppContext::getInstance()->put($pattern, $callable, $conditions);
    }

    public static function delete($pattern, $callable, array $conditions = [])
    {
        return AppContext::getInstance()->delete($pattern, $callable, $conditions);
    }

    public static function viewsFolder()
    {
        $folder = Config::get("SOURCE_DIR") . "views/";
        AppContext::getInstance()->render->setViewsFolder($folder);
    }

    public static function setHF(string $header = "", string $footer = "")
    {
        AppContext::getInstance()->render->setHf($header, $footer);
    }

    public static function add(array $routeNames = [], array $middewares)
    {
        AppContext::getInstance()->add($routeNames, $middewares);
    }

    public static function run()
    {
        AppContext::getInstance()->run();
    }
}
