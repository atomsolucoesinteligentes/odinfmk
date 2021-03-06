<?php

namespace Freya\orm;

use \PDO;
use Freya\orm\IDatabase;
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
        try {
            $this->connection = new PDO("{$this->driver->DRIVER}:host={$this->driver->HOST};port={$this->driver->PORT};dbname={$this->driver->SCHEMA};charset=utf8", $this->driver->USERNAME, $this->driver->PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("set names utf8");
            
            return !empty($this->connection);
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function setConnection(\Freya\orm\Connection $connection)
    {
        $this->connection = new \PDO("{$connection->getDriver()}:host={$connection->getHost()};port={$connection->getPort()};dbname={$connection->getSchema()};charset=utf8", $connection->getUsername(), $connection->getPassword());
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->exec("set names utf8");
        
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
    public function insert($tableName, $values, $showSql = false)
    {
        $valuesstr = [];
        $columns = [];
        $blacklist = ["_tableName", "_tableAlias", "_adapter", "_whereStorage", "_operatorsSequence", "_joins", "_joinsOn", "_aggr", "_limit"];
        foreach(array_slice($values, 10) as $key => $value)
        {
             if(!in_array($key, $blacklist)) {
                $columns[] = "`{$key}`";
                $valuesstr[] = $this->typeFormat($value);
             }
        }

        $columns = implode(",", $columns);
        $valuesstr = implode(",", $valuesstr);
        $query = "INSERT INTO $tableName ($columns) VALUES ($valuesstr)";
        if($showSql) echo $query;
        
        $stmt = $this->connection->query($query);
        $stmt->lastInsertedId = $this->getLastInserted();
        
        return $stmt;
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
    public function update($tableName, $values, $conditions, $showSql = false) 
    {
        $updateString = $this->generateUpdateString($values);
        $whereString = $this->generateWhereString($conditions);
        $query = "UPDATE $tableName SET $updateString WHERE $whereString";
        if($showSql) echo $query;
        
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
    
    public function execute($sql) {
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute();
        return $result;
        
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
        $blacklist = ["_tableName", "_tableAlias", "_adapter", "_whereStorage", "_operatorsSequence", "_joins", "_joinsOn", "_aggr", "_limit"];
        foreach(array_slice($values, 10) as $key => $value)
        {
            if(!in_array($key, $blacklist)) {
                $buildString[] = "`{$key}` = {$this->typeFormat($value)}";
            }
        }
        return implode(", ", $buildString);
    }

    /**94875603
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
    
    public function getLastInserted() {
        return $this->connection->lastInsertId();
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
