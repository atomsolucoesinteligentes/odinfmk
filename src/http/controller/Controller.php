<?php

/**
 * @author Edney Mesquita
 */

namespace Odin\http\controller;

use Odin\http\controller\IController;

abstract class Controller implements IController
{

    protected $container;

    public function __construct(\Odin\routes\Container $container)
    {
        $this->container = $container;
    }

    public function __get($key)
    {
        if ($key === "container") {
            return $this->container;
        }

        if ($this->container->{$key}) {
            return $this->container->{$key};
        }
    }

}
