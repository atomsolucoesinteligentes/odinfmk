<?php

namespace Freya\orm;

use Odin\utils\Parse;
use Odin\utils\Errors;
use Odin\utils\Filter;
use Freya\orm\Collection;
use Freya\orm\Connection;

class QueryBuilder 
{

    private $query;
    protected $connection;
    protected $driver;

    public function __construct(string $query = "", Connection $conn = null) 
    {
        $this->query = $query;
        if(!is_null($conn))
            $this->openConnection ($conn);
        else
            $this->openConnection();
        return $this;
    }

    public function raw(string $query) 
    {
        $this->query = $query;
        return $this;
    }

    public function run() 
    {
        $query = Filter::clear(trim($this->query));
        $stmt = $this->connection->query($this->query);
        if (strpos($query, "SELECT") === 0) {
            return $this->generateCollection($stmt->fetchAll(\PDO::FETCH_OBJ));
        } else {
            return $stmt;
        }
    }

    protected function openConnection(Connection $conn = null) 
    {
        $dbcString = "";
        $username = "";
        $password = "";
        if(!is_null($conn)) {
            $driver = $conn->getDriver();
            $host = $conn->getHost();
            $port = $conn->getPort();
            $schema = $conn->getSchema();
            $username = $conn->getUsername();
            $password = $conn->getPassword();
            $dbcString = "{$driver}:host={$host};port={$port};dbname={$schema}";
        } else {
            $this->driver = Parse::env(ODIN_ROOT . "/" . SOURCE_DIR . "config/" . DRIVER . ".env", true);
            $username = $this->driver->USERNAME;
            $password = $this->driver->PASSWORD;
            $dbcString = "{$this->driver->DRIVER}:host={$this->driver->HOST};port={$this->driver->PORT};dbname={$this->driver->SCHEMA}";
        }
        
        try {
            $this->connection = new \PDO($dbcString, $username, $password);
        } catch (\PDOException $e) {
            Errors::throwError("Não foi possível conectar ao Banco de Dados", $e->getMessage(), "bug");
        }
    }

    protected function generateCollection($data) 
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $item) {
                $collection = new Collection();
                foreach ($item as $key => $value) {
                    $collection->{$key} = $value;
                }
                $result[] = $collection;
            }
            return $result;
        } else {
            $collection = new Collection();
            foreach ($data as $key => $value) {
                $collection->{$key} = $value;
            }
            return $collection;
        }
    }

}
