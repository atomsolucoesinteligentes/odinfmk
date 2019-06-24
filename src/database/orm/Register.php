<?php

namespace Freya\orm;

use Freya\orm\ORMMapper;

class Register
{
    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }

    public function remove($conditions = null, $keyName = "id")
    {
        $mapper = new ORMMapper();
        if($conditions){
            return $mapper->remove($this->_tn, $conditions);
        }else{
            return $mapper->remove($this->_tn, ["{$keyName}" => ['=', $this->{$keyName}, '']]);
        }
    }
}
