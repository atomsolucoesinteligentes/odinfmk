<?php

namespace Odin\utils;

/**
 * Description of Functions
 *
 * @author Edney Mesquita
 */
class Functions {
    
    public static function debug($var, $print_r = true)
    {
        echo "<pre>";
            if($print_r)
                print_r($var);
            else
                var_dump($var);
        echo "</pre>";
    }
}
