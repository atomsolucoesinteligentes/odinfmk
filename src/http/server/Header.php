<?php

namespace Odin\http\server;

class Header
{
    public static function location(string $href)
    {
        header("Location: {$href}");
    }

    public function contentType(string $mime)
    {
        header("Content-Type: {$mime}");
    }

    public static function set(string $property, string $value)
    {
        header("{$property}: {$value}");
    }
}
