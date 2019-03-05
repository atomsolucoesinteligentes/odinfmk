<?php

/*
 * Classe para acessar as variaveis passadas por POST
 */

namespace Odin\utils\superglobals;

class Post
{
    private function __construct(){}
        
    /**
     * Retorna todas as varáveis POST
     * @return array
     */
    public static function all()
    {
        return $_POST;
    }

    /**
     * Verifica se o valor contido numa variável é igual a determinado valor
     * @param string $name
     * @param $value
     * @return boolean
     */
    public static function equals($name, $value)
    {
        return ($_POST[$name] === $value);
    }

    /**
     * Verifica se uma variável post existe
     * @param string $name
     * @return boolean
     */
    public static function exists($name)
    {
        return isset($_POST[$name]);
    }

    /**
     * Verifica se uma variável é vazia
     * @param string $name
     * @param bool $v
     * @param mixed $return
     * @return bool|mixed
     */
    public static function isEmpty($name, $v = false, $return = false)
    {
        if(empty($_POST[$name])){
            if($return !== false){
                return $return;
            }
            return false;
        }
        return ($v ? $_POST[$name] : true);
    }

    /**
     * Apaga todas as variáveis post
     * @return void
     */
    public static function flush()
    {
        $_POST = [];
    }

    /**
     * Apaga uma determinada variável post
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
        return isset($_POST[$name]) ? $_POST[$name] : NULL;
    }

    /**
     * Insere ou edita o valor de uma variável post
     * @param string $name
     * @param $value
     * @return void
     */
    public static function put($name, $value)
    {
        $_POST[$name] = $value;
    }

    /**
     * Insere um novo elemento caso a variável mencionada seja um array
     * @param string $name
     * @param array $value
     * @return void
     */
    public static function push($name, array $value)
    {
        if(is_array($_POST[$name])){
            array_push($_POST[$name], $value);
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
