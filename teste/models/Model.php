<?php

namespace App\models;

use Freya\orm\ORMMapper;

class Model extends ORMMapper
{
    private $tableName = "quadros";

    public function __construct()
    {
    	parent::__construct();
    	parent::setTableName($this->tableName);
    }
}