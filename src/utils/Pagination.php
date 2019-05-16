<?php

namespace Odin\utils;

class Pagination 
{
    private static $length = 10;
    
    public static function make(array $itens, int $page = 1, string $varname = "results", bool $reverse = false)
    {
        $length = self::$length;
        $total = ceil(count($itens) / $length);
        $offset = ($page - 1) * $length;
        
        if($reverse)
            $itens = array_reverse($itens);
        $result = [
            "{$varname}" => array_slice($itens, $offset, $length),
            "total" => $total,
            "current" => $page
        ];
        
        return $result;
    }
    
    public static function setLength(int $length)
    {
        self::$length = $length;
    }
}