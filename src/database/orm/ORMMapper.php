<?php

namespace Odin\database\orm;

use Odin\database\orm\{Adapter, IMapper, Collection};

class ORMMapper implements IMapper
{

    private $_tableName = "";

    private $_tableAlias = "";

    private $_adapter;

    private $_whereStorage = [];

    private $_operatorSequence = [];

    private $_joins = [];

    private $_joinsOn = [];

    private $_aggr = [];

    private $_limit = "";

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
        return $this->generateCollection($result);
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
            return $this->generateCollection($result[0]);
        }
        return (object)[];
    }

    public function where(array $conditions, $operator)
    {
        foreach($conditions as $field => $value)
        {
            $opv = "";
            switch (strtoupper($operator)) {
                case 'BETWEEN':
                    if(is_array($value)){
                        $opv = "BETWEEN {$this->_adapter->typeFormat($value[0])} AND {$this->_adapter->typeFormat($value[1])}";
                    }else{
                        die("O valor para o operador BETWEEN deve ser um array de duas posições.");
                    }
                    break;
                case 'NOT BETWEEN':
                    if(is_array($value)){
                        $opv = "NOT BETWEEN {$this->_adapter->typeFormat($value[0])} AND {$this->_adapter->typeFormat($value[1])}";
                    }else{
                        die("O valor para o operador NOT BETWEEN deve ser um array de duas posições.");
                    }
                    break;
                case 'LIKE':
                    $opv = "LIKE '%{$value}%'";
                    break;
                case 'LIKE_L':
                    $opv = "LIKE '%{$value}'";
                    break;
                case 'LIKE_R':
                    $opv = "LIKE '{$value}%'";
                    break;
                case 'IN':
                    if(is_array($value)){
                        $ins = [];
                        foreach($value as $vin)
                        {
                            $ins[] = $this->_adapter->typeFormat($vin);
                        }
                        $ins = implode(",", $ins);
                        $opv = "IN ({$ins})";
                    }else{
                        die("O valor para o operador IN deve ser um array.");
                    }
                    break;
                case 'NOT IN':
                    if(is_array($value)){
                        $ins = [];
                        foreach($value as $vin)
                        {
                            $ins[] = $this->_adapter->typeFormat($vin);
                        }
                        $ins = implode(",", $ins);
                        $opv = "NOT IN ({$ins})";
                    }else{
                        die("O valor para o operador NOT IN deve ser um array.");
                    }
                    break;
                default:
                    $opv = $operator ." ". $this->_adapter->typeFormat($value);
                    break;
            }
            $field = (strpos($field, ".") == false) ? "`$field`" : $field;
            $this->_whereStorage[] = "{$field} {$opv}";
        }
        return $this;
    }

    public function and()
    {
        $this->_operatorsSequence[] = "AND";
        return $this;
    }

    public function or()
    {
        $this->_operatorsSequence[] = "OR";
        return $this;
    }

    public function innerJoin($tableJoin)
    {
        $this->_joins[] = "INNER JOIN {$tableJoin}";
        return $this;
    }

    public function leftJoin($tableJoin)
    {
        $this->_joins[] = "LEFT JOIN {$tableJoin}";
        return $this;
    }

    public function rightJoin($tableJoin)
    {
        $this->_joins[] = "RIGHT JOIN {$tableJoin}";
        return $this;
    }

    public function on($condition)
    {
        $this->_joinsOn[] = "ON({$condition})";
        return $this;
    }

    public function orderBy($column, $ord)
    {
        $ord = strtoupper($ord);
        $this->_aggr[] = "ORDER BY {$column} {$ord}";
        return $this;
    }

    public function groupBy($column)
    {
        $this->_aggr[] = "GROUP BY {$column}";
        return $this;
    }

    public function having($condition)
    {
        $this->_aggr[] = "HAVING $condition";
        return $this;
    }

    public function limit($limit, $offset = "")
    {
        $this->_limit = empty($offset) ? "LIMIT {$limit}" : "LIMIT {$limit}, {$offset}";
        return $this;
    }

    public function get($columns = "*")
    {
        $joins = [];
        if(!empty($this->_joins) && !empty($this->_joinsOn)){
            foreach($this->_joins as $key => $join)
            {
                $on = $this->_joinsOn[$key];
                $joins[] = "{$join} {$on}";
            }
            $joins = implode(" ", $joins);
        }
        $where = "";
        if(!empty($this->generateWS())){
            $where = "WHERE {$this->generateWS()}";
        }
        $agreg = "";
        if(!empty($this->_aggr)){
            sort($this->_aggr);
            $agreg = implode(" ", $this->_aggr);
        }
        $limit = $this->_limit;
        $joins = (empty($joins) ? "" : $joins);
        $query = "SELECT {$columns} FROM {$this->_tableName} {$this->_tableAlias} {$joins} {$where} {$agreg} {$limit}";
        echo $query;
        // $result = $this->_adapter->query($query);
        // return $this->generateCollection($result);
    }

    protected function generateWS()
    {
        $whereString = [];
        foreach($this->_whereStorage as $key => $item)
        {
            $whereString[] = $item . " " . @(!empty($this->_operatorsSequence) ? $this->_operatorsSequence[$key] : "");
        }
        return implode(" ", $whereString);
    }

    protected function generateCollection($data)
    {
        if(is_array($data)){
            $result = [];
            foreach($data as $item)
            {
                $collection = new Collection();
                $collection->_tn = $this->_tableName;
                foreach($item as $key => $value)
                {
                    $collection->{$key} = $value;
                }
                $result[] = $collection;
            }
            return $result;
        } else {
            $collection = new Collection();
            $collection->_tn = $this->_tableName;
            foreach($data as $key => $value)
            {
                $collection->{$key} = $value;
            }
            return $collection;
        }
    }

    /**
     * @return mixed
     */
    public function save()
    {
        if (isset($this->id)) {
            return $this->_adapter->update($this->_tableName, (array)$this, ['id' => ['=', $this->id, '']]);
        }
        return $this->_adapter->insert($this->_tableName, (array)$this);
    }

    public function loadClassProperties()
    {
        $fields = $this->_adapter->fetch($this->_tableName);
        foreach ($fields as $field) {
            $this->$field = null;
        }
    }

    public function remove($tableName, $conditions)
    {
        return $this->_adapter->delete($tableName, $conditions);
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
    public function setTableName($tableName, $alias = "")
    {
        $this->_tableName = $tableName;
        if(!empty($alias))
            $this->_tableAlias = $alias;
    }
}