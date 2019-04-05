<?php

namespace Odin\utils;

class Input
{

    /**
     * Captura dados enviados via GET e opcionalmente filtra-os
     * @param string $nome
     * @param int $filtro
     * @return mixed
     */
    public static function get(string $nome, int $filtro = FILTER_DEFAULT)
    {
        return filter_input(INPUT_GET, $nome, $filtro);
    }
   
    /**
     * Captura dados enviados via POST e opcionalmente filtra-os
     * @param string $nome
     * @param int $filtro
     * @return mixed
     */
    public static function post(string $nome, int $filtro = FILTER_DEFAULT)
    {
        return filter_input(INPUT_POST, $nome, $filtro);
    }
    
    /**
     * Captura todos os dados enviados via GET ou POST em um array e opcionalmente filtra-os
     * @param int $tipo
     * @param int $filtro
     * @return array
     */
    public static function all(int $tipo, int $filtro = FILTER_DEFAULT)
    {
        return filter_input_array($tipo, $filtro);
    }
}