<?php

namespace App\models;

use Freya\orm\ORMMapper;

class Model extends ORMMapper
{
    private $tableName = "teste";

    public function __construct()
    {
    	parent::__construct(false);
    	parent::setTableName($this->tableName);
    }
}
