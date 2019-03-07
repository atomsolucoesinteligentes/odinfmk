<?php

namespace Odin\database;

use Odin\database\{Adapter, IMapper};

class ORMMapper implements IMapper
{

    private $_tableName = '';

    private $_adapter;

    public function __construct()
    {
        $this->_adapter = new Adapter();
        if (!$this->_adapter->connect()) {
            echo "Não foi possível conectar ao banco";
            return;
        }
        $this->loadClassProperties();
    }

    /**
     * @return object
     */
    public function findAll()
    {
        $result = $this->_adapter->select($this->_tableName, '*', []);
        return $this->buildResponseObject($result);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $result = $this->_adapter->select($this->_tableName, '*', ['id' => ['=', $id, '']]);
        $result = $this->buildResponseObject($result);
        if ($result){
            return $result[0];
        }
        return (object)[];
    }

    public function where()
    {

    }


    /**
     * @return mixed
     */
    public function save()
    {
        // todo: Complete the Implementation of this method
        $fields = $this->_adapter->fetch($this->_tableName);
        if (isset($this->id)) {
            return $this->_adapter->update($this->_tableName, $fields, (array)$this, ['id' => ['=', $this->id, '']]);
        }
        return $this->_adapter->insert($this->_tableName, $fields, (array)$this);
    }

    public function loadClassProperties()
    {
        $fields = $this->_adapter->fetch($this->_tableName);
        foreach ($fields as $field) {
            $this->$field = null;
        }
    }

    /**
     * @param $result
     * @return object
     */
    public function buildResponseObject($result)
    {
        $response = [];
        if ($result) {
            $response = $result;
        }
        return $response;
    }


    /**
     * @param $tableName
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
    }
}
