<?php

namespace Easy\utils;

use Easy\utils\Config;

/**
 * Gerencia os Loggers das aplicações
 */
class Logger {

    /**
     * @var string $filename
     */
    private $filename;

    /**
     * @var string $basedir
     */
    private $basedir;

    /**
     * Configura o $basedir (Diretório padrão)
     */
    public function __construct() {
        $this->basedir = Config::get('LOGS_PATH');
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
