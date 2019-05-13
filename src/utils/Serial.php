<?php

namespace Odin\utils;

final class Serial
{
    public static function validate($serial = "")
    {
        if(empty(ODIN_SERIAL))
            die("Serial não informada");
        if(empty($serial))
            $serial = ODIN_SERIAL;
        if($serial !== false){
            $c = sscanf($serial, '%4s-%4s-%4s-%4s');
            $d = 1;
            for ($i = 0; $i < 4; $i++)
                for ($j = 0; $j < 4; $d += pow(ord($c[$i]{$j}), $i), $j++);
            $c[4] = $d;
            return !strcmp($serial, vsprintf('%s-%s-%s-%s-%05x', $c));
        }else{
            return false;
        }
    }
}
