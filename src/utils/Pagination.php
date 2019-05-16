<?php

namespace Odin\utils;

use Odin\utils\collections\{ArrayList, Dictionary};

class Pagination 
{
    private static $length = 10;
    
    public static function make($itens, int $page = 1, string $varname = "results", bool $reverse = false)
    {
        $result = null;
        if(!$itens instanceof ArrayList && !$itens instanceof Dictionary && is_array($itens)){
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
        }else{
            $result = self::collection($itens, $page, $varname, $reverse);
        }
        
        return $result;
    }
    
    protected static function collection($collection, $page, $varname)
    {
        $length = self::$length;
        $total = ceil($collection->size() / $length);
        $offset = ($page - 1) * $length;

        $result = [
            "{$varname}" => $collection->slice($offset, $length),
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