<?php

namespace Odin\database;

use \PDO;
use \Exception;

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
    public static function open($name)
    {
        $dbname = 'atomsigea';
        $host = '186.202.152.175';
        $port = '3306';
        $password = 'dbsigea';
        $username = 'atomsigea';

        $conn = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->query("SET NAMES utf8");
        $conn->query("SET CHARACTER SET utf8");
        return $conn;
    }
}
