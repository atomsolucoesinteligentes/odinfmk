<?php

namespace Odin\http\server;

trait HttpData
{

    public $data = [];

    public function dataExists($key)
    {
        return (isset($this->data[$key]));
    }

    public function __set($key, $val)
    {
        $this->data[$key] = $val;
    }

    public function __get($key)
    {
        return $this->data[$key];
    }
}
