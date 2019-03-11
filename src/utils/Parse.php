<?php

namespace Odin\utils;

class Parse
{

    protected static function getFileContent(string $path, bool $asArray = false)
    {
        if($asArray){
            return file($path);
        }else{
            return file_get_content($path);
        }
    }

    public static function env(string $content, bool $isFile = false)
    {
        $lines = ((!$isFile) ? $content : self::getFileContent($content, true));
        $parsed = new \stdClass();
        foreach($lines as $line)
        {
            if(!empty(trim($line)) && strpos(trim($line), "#") === false){
                $lineq = explode("=", $line);
                $parsed->{$lineq[0]} = trim($lineq[1]);
            }
        }
        return $parsed;
    }

    public static function json(string $content, bool $isFile = false)
    {
        return json_decode(((!$isFile) ? $content : self::getFileContent($content)));
    }

    public static function ini(string $path)
    {
        return parse_ini_file($path);
    }
}
