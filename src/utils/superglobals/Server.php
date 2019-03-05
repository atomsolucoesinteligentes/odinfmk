<?php

/**
 * Gerencia o registro do servidor
 * @author Edney Mesquita
 */

namespace Odin\utils\superglobals;

class Server
{

    /**
     * Retorna o valor de uma variável do servidor
     * @param string $name
     * @return string
     */
    public static function get($name)
    {
        return $_SERVER[$name];
    }

    /**
     * Insere ou edita o valor de uma variável
     * @param string $name
     * @param $value
     * @return void
     */
    public static function put($name, $value)
    {
        $_SERVER[$name] = $value;
    }

    /**
     * Verifica se uma variável existe
     * @param string $name
     * @return bool
     */
    public static function exists($name)
    {
        return isset($_SERVER[$name]);
    }

    /**
     * Verifica se o valor de uma variável e igual ao valor passado
     * @param string $name
     * @param $valor
     * @return bool
     */
    public static function equals($name, $value)
    {
        return ($_SERVER[$name] === $value);
    }

    /**
     * Retorna todas as variáveis
     * @return array
     */
    public static function all()
    {
        return $_SERVER;
    }

    /**
     * Exclui o valor de uma variável
     * @param string $name
     * @return void
     */
    public static function forget($name)
    {
        $this->put($name, '');
    }

    /**
     * Exlcui todas as variáveis
     * @return void
     */
    public static function flush()
    {
        $_SERVER = [];
    }

}
