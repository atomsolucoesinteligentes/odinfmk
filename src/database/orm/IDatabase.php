<?php

namespace Freya\orm;

interface IDatabase
{

    /**
     * @return bool
     */
    public function connect();

    /**
     * @return void
     */
    public function disconnect();

    /**
     * @param string $tableName
     * @param array $columns
     * @param array $values
     * @return mixed
     */
    public function insert($tableName, $values);

    /**
     * @param string $tableName
     * @param array $conditions
     * @param array $columns
     * @param array $values
     * @return mixed
     */
    public function update($tableName, $values, $conditions);

    /**
     * @param string $tableName
     * @param string $columns
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function select($tableName, $columns,  $conditions, $limit, $offset);

    /**
     * @param string $tableName
     * @param array $conditions
     * @return mixed
     */
    public function delete($tableName, $conditions);

    /**
     * @param string $tableName
     * @return array
     */
    public function fetch($tableName);
}
