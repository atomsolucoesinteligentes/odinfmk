<?php

namespace Easy\utils;

use Easy\utils\Config;

/**
 * Gerencia as operações com o banco de dados
 * @version 1.0.9
 * @package Easy\utils
 * @author Edney Mesquita
 */
class Logger {

    /**
     * @var $filename string nome do arquivo do log
     * @var $basedir string diretório padrão do arquivo
     */
    private $filename;
    private $basedir;

    /**
     * Configura o $basedir (Diretório padrão)
     */
    public function __construct() {
        $this->basedir = Config::get('log_files_path');
    }

    /**
     * Define o nome do arquivo com base no parâmetro e na data atual
     * @param string $filename nome do arquivo do log
     */
    public function open(string $filename) {
        $time = date("Y-m-d");
        $this->filename = $filename . '_' . $time . '.log';
    }

    /**
     * Define o conteúdo da mensagem que será gravada no log
     * @param string $message mensagem para ser gravado no log
     */
    public function write(string $message) {
        $time = date("Y-m-d H:i:s");
        $text = "$time :: $message\n";
        $handler = fopen($this->basedir . $this->filename, 'a+');
        fwrite($handler, $text);
        fclose($handler);
    }

}
