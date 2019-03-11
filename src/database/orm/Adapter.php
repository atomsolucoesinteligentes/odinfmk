<?php

namespace Odin\database\orm;

use \PDO;
use Odin\database\orm\IDatabase;
use Odin\utils\Parse;

class Adapter implements IDatabase
{

    private $connection;

    private $driver;

    public function __construct()
    {
        $this->driver = Parse::env(ODIN_ROOT."/".SOURCE_DIR."config/".DRIVER.".env", true);
    }

    public function connect()
    {
        $this->connection = new \PDO("{$this->driver->DRIVER}:host={$this->driver->HOST};port={$this->driver->PORT};dbname={$this->driver->SCHEMA}", $this->driver->USERNAME, $this->driver->PASSWORD);
        return !empty($this->connection);
    }

    public function disconnect()
    {
        if (isset($this->connection)) {
            $this->connection = null;
        }
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @param array $values
     * @return mixed
     */
    public function insert($tableName, $values)
    {
        $valuesstr = [];
        $columns = [];
        foreach(array_slice($values, 11) as $key => $value)
        {
            $columns[] = $key;
            $valuesstr[] = $this->typeFormat($value);
        }

        $columns = implode(",", $columns);
        $valuesstr = implode(",", $valuesstr);
        $query = "INSERT INTO $tableName ($columns) VALUES ($valuesstr)";
        return $this->connection->query($query);
    }

    public function typeFormat($value)
    {
        switch (gettype($value))
        {
            case 'integer':
                return intval($value);
            case 'double':
                return doubleval($value);
            case 'NULL':
                return 'NULL';
            case 'string':
                return "'{$value}'";
            default:
                return 'NULL';
        }
    }

    /**
     * @param string $tableName
     * @param array $conditions structure -> column => (operator, value, logical_operator) e.g id => (>, 5, AND)
     * @param array $columns
     * @param array $values
     * @return mixed
     */
    public function update($tableName, $values, $conditions)
    {
        $updateString = $this->generateUpdateString($values);
        $whereString = $this->generateWhereString($conditions);
        $query = "UPDATE $tableName SET $updateString WHERE $whereString";
        $result = $this->connection->query($query);
        return $result;
    }

    /**
     * @param string $tableName
     * @param string $columns
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function select($tableName, $columns, $conditions, $limit = null, $offset = null)
    {
        $query = "SELECT $columns FROM $tableName";
        if (!empty($conditions)) {
            $whereString = $this->generateWhereString($conditions);
            $query .= " WHERE $whereString";
        }
        if (isset($limit) && isset($offset)) {
            $query .= "LIMIT $limit OFFSET $offset";
        }
        $result = $this->connection->query($query);
        $response = [];
        if($result){
            $response = $result->fetchAll(\PDO::FETCH_OBJ);
        }
        return $response;
    }

    public function query($sql)
    {
        $result = $this->connection->query($sql);
        $response = [];
        if($result){
            $response = $result->fetchAll(\PDO::FETCH_OBJ);
        }
        return $response;
    }

    /**
     * @param string $tableName
     * @param array $conditions
     * @return mixed
     */
    public function delete($tableName, $conditions)
    {
        $whereString = $this->generateWhereString($conditions);
        $query = "DELETE FROM $tableName WHERE $whereString";
        $result = $this->connection->query($query);
        return $result;
    }

    /**
     * @param array $keys
     * @param array $values
     * @return string
     */
    public function generateUpdateString($values)
    {
        $buildString = [];
        foreach(array_slice($values, 11) as $key => $value)
        {
            $buildString[] = "`{$key}` = {$this->typeFormat($value)}";
        }
        return implode(", ", $buildString);
    }

    /**
     * @param array $arrayValues
     * @return string
     */
    public function generateWhereString($arrayValues)
    {
        $buildString = '';
        foreach ($arrayValues as $key => $arrayValue) {
            $buildString .= "`{$key}`" . $arrayValue[0] . $arrayValue[1] . " " . $arrayValue[2];
        }
        return $buildString;
    }

    public function prepareResultQuery($tableName)
    {
        return $this->connection->query("SELECT * FROM {$tableName}");
    }

    /**
     * @param string $queryResult
     * @return array
     */
    public function fetch($queryResult)
    {
        if ($queryResult) {
            $fieldsData = $queryResult->fetch(\PDO::FETCH_OBJ);
            $fields = array_keys(get_object_vars($fieldsData));
            return $fields;
        }
        return [];
    }
}
