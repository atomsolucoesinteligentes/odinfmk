<?php

/**
 * @author Edney Mesquita
 */

namespace Odin\routes;

class Container
{

    /**
     * Array de dependencias
     * @var $data array
     */
    private $data;

    public function __construct(array $params)
    {
        $this->setData($params);
    }

    /**
     * Guarda os parametros passados para Container na propriedade $data
     * @return void
     */
    private function setData(array $params)
    {
        if (!is_array($params)) {
            \Easy\utils\Errors::throwError('Bug!', 'Dados do container deve ser um array.', 'bug');
        }

        $this->data = $params;
    }

    /**
     * Retorna o Array de dependencias
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * recebe a chamada $obj->property e cria um novo indice para o mesmo no
     * array de dependencias
     */
    public function __set($key, $val)
    {
        $this->data[$key] = $val;
    }

    /**
     * Intercepta o $obj->property e caso exista no array de dependencias
     * retorna seu valor. Caso seja um closure, o retorna executado.
     */
    public function __get($key)
    {
        if ($this->data[$key]) {
            if ($this->data[$key] instanceof \Closure) {
                $fnc = $this->data[$key];
                return $fnc();
            } else {
                return $this->data[$key];
            }
        }
    }

    /*     * *
     * Cria instancias compartilhadas dentro do container,
     * Persistindo o retorno de um dado closure ao longo de sua execução.
     */

    public function shared(\Closure $callable)
    {
        return function () use ($callable)
        {
            static $object;

            if (is_null($object)) {
                $object = $callable();
            }

            return $object;
        };
    }

}
