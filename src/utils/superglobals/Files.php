<?php

/**
 * Gerencia os arquivos
 * @info Easy Framework
 * @version 1.1.6
 * @author Edney Mesquita
 * @package Easy\utils\superglobals
 */

namespace Easy\utils\superglobals;

use Easy\utils\superglobals\ISuperGlobals;

class Files implements ISuperGlobals {

    private $file;
    
    public function __construct() {
        $this->file = $_FILES;
    }

    /**
     * Retorna o valor de uma propriedade
     * @param string $name
     * @return string
     */
    public function get($name){
        return $this->file[$name];
    }

    /**
     * Insere ou edita o valor de uma propriedade
     * @param string $var
     * @param string $name
     * @param $value
     * @return void
     */
    public function put($name, $value) {
        $this->file[$name] = $value;
    }

    /**
     * Retorna todas as propriedades
     * @return array
     */
    public function all() {
        return $this->file;
    }

    /**
     * Verifica se uma propriedade existe
     * @param string $name
     * @return boolean
     */
    public function exists($name) {
        return isset($this->file[$name]);
    }

    /**
     * Verifica se o valor contido numa variável é igual a determinado valor
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function equals($name, $value) {
        return ($this->file[$name] === $value);
    }

    /**
     * Apaga uma determinada variável
     * @param string $name
     * @return void
     */
    public function forget($name) {
        $this->put($name, "");
    }

    /**
     * Apaga todas as propriedades
     * @return void
     */
    public function flush() {
        $this->file = [];
    }
}
?>
