<?php

namespace Odin\database;

use \PDO;
use \Exception;
use Odin\utils\Config;

/**
 * Classe para definição de parâmetros de conexão com o sgbd
 *
 * @author Edney Mesquita
 */
final class Connection
{

    /**
     * Recebe o nome do conector (SGBD) e instancia o objeto PDO
     * @param string $name nome do SGBD
     * @throws Exception
     * @return mixed
     */
    public static function open()
    {
        $name = Config::get("sgbd");
        $file = ODIN_ROOT . "/test/config/{$name}.php";
        if (file_exists($file)) {
            //converte o arquivo em uma array
            require_once $file;
        } else {
            //se não existir, lança um erro
            $erro = "Arquivo '{$name}' não encontrado em {$file}.";
            throw new Exception($erro);
        }

        //lê as informações e as armazena
        $type = defined('DB_TYPE') ? DB_TYPE : null;
        $host = defined('DB_HOST') ? DB_HOST : null;
        $port = defined('DB_PORT') ? DB_PORT : null;
        $username = defined('DB_USER') ? DB_USER : null;
        $password = defined('DB_PASS') ? DB_PASS : null;
        $dbname = defined('DB_NAME') ? DB_NAME : null;


        //determina o tipo (driver) do sgbd a ser usado
        switch ($type) {
            case 'mysql':
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $username, $password);
                break;
            case 'pgsql':
                $conn = new PDO("pgsql:host={$host};port={$port};dbname={$dbname}", $username, $password);
                break;
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->query("SET NAMES utf8");
        $conn->query("SET CHARACTER SET utf8");
        return $conn;
    }
}
