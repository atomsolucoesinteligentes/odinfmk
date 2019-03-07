<?php

namespace Odin\database;

use Odin\database\IDatabase;
use Odin\database\Transaction;

class Adapter implements IDatabase
{

    private $connection;

    public function __construct()
    {
    }

    public function connect()
    {
        Transaction::open();
        $this->connection = Transaction::get();
        return !empty($this->connection);
    }

    public function disconnect()
    {
        if (isset($this->connection)) {
            Transaction::close();
        }
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @param array $values
     * @return mixed
     */
    public function insert($tableName, $columns, $values)
    {
        $query = "INSERT INTO $tableName $columns VALUES $values";
        return $this->connection->query($query);
    }

    /**
     * @param string $tableName
     * @param array $conditions structure -> column => (operator, value, logical_operator) e.g id => (>, 5, AND)
     * @param array $columns
     * @param array $values
     * @return mixed
     */
    public function update($tableName, $columns, $values, $conditions)
    {
        $updateString = $this->generateUpdateString($columns, $values);
        $whereString = $this->generateWhereString($conditions);
        $query = "UPDATE $tableName SET  $updateString WHERE  $whereString";
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

    /**
     * @param string $tableName
     * @param array $conditions
     * @return mixed
     */
    public function delete($tableName, $conditions)
    {
        $whereString = $this->generateWhereString($conditions);
        $query = "DELETE FROM $tableName WHERE $whereString";
        return $query;
    }

    /**
     * @param array $keys
     * @param array $values
     * @return string
     */
    public function generateUpdateString($keys, $values)
    {
        $len = count($keys);
        $buildString = '';
        for ($i = 0; $i < $len - 1; $i++) {
            $buildString .= $keys[$i] . '=' . $values[$i] . ',';
        }
        $buildString .= $keys[$len - 1] . '=' . $values[$len - 1];
        return $buildString;
    }

    /**
     * @param array $arrayValues
     * @return string
     */
    public function generateWhereString($arrayValues)
    {
        $buildString = '';
        foreach ($arrayValues as $key => $arrayValue) {
            $buildString .= $key . $arrayValue[0] . $arrayValue[1] . " " . $arrayValue[2];
        }
        return $buildString;
    }

    /**
     * @param string $queryResult
     * @return array
     */
    public function fetch($queryResult)
    {
        if ($queryResult) {
            $fieldsData = $queryResult->fetchAll(\PDO::FETCH_OBJ);
            $fields = [];
            foreach ($fieldsData as $fieldData)
            {
                foreach(get_object_vars($fieldData) as $key => $value)
                {
                    $fields[] = $fieldData->{$key};
                }

            }
            return $fields;
        }
        return [];
    }
}
