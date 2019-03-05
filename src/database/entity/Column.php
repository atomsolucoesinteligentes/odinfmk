<?php

namespace Odin\database\entity;

/**
 * Cria uma representação orientada a objetos das colunas das tabelas e controla-as
 *
 * @author Edney Mesquita
 */
class Column
{
    protected $primaryKey;
    protected $autoIncrement;
    protected $nullable;
    protected $type;
    protected $unsigned;
    
    public function __construct()
    {
        return $this;
    }

    public function primaryKey()
    {
        $this->primaryKey = true;
        return $this;
    }

    public function isPK()
    {
        return $this->primaryKey === true;
    }

    public function autoIncrement()
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function isAutoIncrement()
    {
        return $this->autoIncrement === true;
    }

    public function null()
    {
        $this->nullable = true;
        return $this;
    }

    public function notNull()
    {
        $this->nullable = false;
        return $this;
    }

    public function allowNull()
    {
        return $this->nullable === true;
    }

    public function unsigned()
    {
        $this->unsigned = true;
        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;
        return $this;
    }
}
