<?php

namespace Odin\view;

use Odin\http\controller\IController;
use Odin\utils\Functions;

trait View
{
    public static function render(IController $controller, string $page, array $params = [], bool $hf = true)
    {
        $controller->container->render->load($page, $params, $hf);
    }
}
