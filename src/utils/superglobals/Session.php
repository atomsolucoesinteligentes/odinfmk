<?php

/**
 * Gerencia o registro da sessão
 * @author Edney Mesquita
 */

namespace Odin\utils\superglobals;

class Session
{

    public function __construct() {
        self::init();
    }

    private static function init()
    {
        @session_set_cookie_params(3600);
        @session_start();
    }

    /**
     * Adiciona uma nova variável de sessão à superglobal $_SESSION
     * @param string $name
     * @param $value
     * @return void
     */
    public static function create($name, $value = null)
    {
        static::init();
        if(!empty($name))
            $_SESSION[$name] = $value;
    }

    /**
     * Insere ou edita o valor de uma variável de sessão
     * @param string $name
     * @param $value
     * @return void
     */
    public static function put($name, $value)
    {
        static::init();
        $_SESSION[$name] = $value;
    }

    /**
     * Retorna o valor de uma variável
     * @param string $name
     * @return mixed
     */
    public static function get($name)
    {
        static::init();
        return $_SESSION[$name];
    }

    /**
     * Retorna todas as variáveis de sessão
     * @return array
     */
    public static function all()
    {
        static::init();
        return $_SESSION;
    }

    /**
     * Verifica se uma variável de sessão existe
     * @param string $name
     * @return boolean
     */
    public static function exists($name)
    {
        static::init();
        return isset($_SESSION[$name]);
    }

    /**
     * Verifica se o valor contido numa variável é igual a determinado valor
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public static function equals($name, $value)
    {
        static::init();
        return ($_SESSION[$name] === $value);
    }

    /**
     * Apaga uma determinada variável de sessão
     * @param string $name
     * @return void
     */
    public static function forget($name)
    {
        static::init();
        $this->put($name, "");
    }

    /**
     * Apaga todas as variáveis de sessão
     * @return void
     */
    public static function flush()
    {
        static::init();
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Insere um novo elemento caso a variável mencionada seja um array
     * @param string $name
     * @param array $value
     * @return void
     */
    public static function push($name, array $value)
    {
        static::init();
        if(is_array($_SESSION[$name])){
            array_push($_SESSION[$name], $value);
        }
    }

    /**
     * Retorna um valor e em seguida exclui a variável
     * @param string $name
     */
    public static function pull($name)
    {
        static::init();
        $this->get($name);
        $this->forget($name);
    }

}
