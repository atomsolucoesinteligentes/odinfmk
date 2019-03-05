<?php

/**
 * Gerencia o registro de cookies
 * @author Edney Mesquita
 */

namespace Odin\utils\superglobals;

class Cookie
{
    private function __construct(){}
        
    /**
     * Cria um novo cookie
     * @param string $name
     * @param int $expiration
     * @param mixed $value
     * @return void
     */
    public function create($name, $expiration, $value = null)
    {
        setcookie($name, $value, $this->timeExpiration($expiration));
        if(!empty($value)){
            $this->set($name, $value);
        }
    }

    /**
     * Converte as horas para segundos
     * @param int $hours
     * @return int
     */
    protected function timeExpiration($hours)
    {
        return time() + (60 * 60) * $hours;
    }

    /**
     * Insere ou edita o valor de um cookie
     * @param string $name
     * @param $value
     * @return void
     */
    public function put($name, $value)
    {
        $_COOKIE[$name] = $value;
    }

    /**
     * Retorna o valor de um cookie
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
       return $_COOKIE[$name];
    }

    /**
     * Retorna todos os cookies
     * @return array
     */
    public function all()
    {
        return $_COOKIE;
    }

    /**
     * Verifica se um cookie existe
     * @param string $name
     * @return boolean
     */
    public function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Verifica se o valor contido num cookie é igual a determinado valor
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function equals($name, $value)
    {
        return ($_COOKIE[$name] === $value);
    }

    /**
     * Apaga um determinado cookie
     * @param string $name
     * @return void
     */
    public function forget($name)
    {
        $this->put($name, "");
    }

    /**
     * Apaga todas os cookies
     * @return void
     */
    public function flush()
    {
        foreach($_COOKIE as $name => $item)
        {
            setcookie($name, "", time() - 3600);
        }
    }

}
