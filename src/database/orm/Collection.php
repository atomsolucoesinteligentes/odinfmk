<?php

namespace Odin\database\orm;

use Odin\database\orm\ORMMapper;

class Collection
{
    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }

    public function remove($conditions = null)
    {
        $mapper = new ORMMapper();
        if($conditions){
            $mapper->remove($this->_tn, $conditions);
        }else{
            $mapper->remove($this->_tn, ['id' => ['=', $this->id, '']]);
        }
    }
}
