<?php

namespace Odin\database;

use Odin\database\operations\Finders;
use Odin\utils\Functions;

class Database
{
    use Finders;

    public $where;
    public $calledClass;
    private $conn;

    public function __construct()
    {
        Transaction::open("teste");
        $this->conn = Transaction::get();
    }

    public function get()
    {
        $table = strtolower(@end(explode("\\", $this->calledClass)));
        Functions::debug($this->_select($table));
    }

    private function _select(string $table)
    {
        $sql = "SELECT * FROM {$table} WHERE " . $this->where;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $data = $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
