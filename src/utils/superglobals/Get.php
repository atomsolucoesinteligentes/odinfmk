<?php

/*
 * Classe para acessar as variaveis passadas por GET
 */

namespace Odin\utils\superglobals;

class Get
{

    private function __construct(){}
        
    /**
     * Retorna todas as varáveis GET
     * @return array
     */
    public static function all()
    {
        return $_GET;
    }

    /**
     * Verifica se o valor contido numa variável é igual a determinado valor
     * @param string $name
     * @param $value
     * @return boolean
     */
    public static function equals($name, $value)
    {
        return ($_GET[$name] === $value);
    }

    /**
     * Verifica se uma variável get existe
     * @param string $name
     * @return boolean
     */
    public static function exists($name)
    {
        return isset($_GET[$name]);
    }

    /**
     * Apaga todas as variáveis get
     * @return void
     */
    public static function flush()
    {
        $_GET = [];
    }

    /**
     * Apaga uma determinada variável get
     * @param string $name
     * @return void
     */
    public static function forget($name)
    {
        $this->put($name, "");
    }

    /**
     * Retorna o valor de uma variável
     * @param string $name
     * @return mixed
     */
    public static function get($name)
    {
        return $_GET[$name];
    }

    /**
     * Insere ou edita o valor de uma variável get
     * @param string $name
     * @param $value
     * @return void
     */
    public static function put($name, $value)
    {
        $_GET[$name] = $value;
    }

    /**
     * Insere um novo elemento caso a variável mencionada seja um array
     * @param string $name
     * @param array $value
     * @return void
     */
    public static function push($name, array $value)
    {
        if(is_array($_GET[$name])){
            array_push($_GET[$name], $value);
        }
    }

    /**
     * Retorna um valor e em seguida exclui a variável
     * @param string $name
     */
    public static function pull($name)
    {
        $this->get($name);
        $this->forget($name);
    }

}
