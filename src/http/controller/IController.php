<?php

namespace Odin\http\controller;

use Odin\routes\Container;

interface IController
{
    public function __construct(Container $container);
    public function __get($key);
}
