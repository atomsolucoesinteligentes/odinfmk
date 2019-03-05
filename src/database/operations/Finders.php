<?php

namespace Odin\database\operations;

use Odin\database\operations\Operators;

trait Finders
{
    public static $whereStorage = [];
    public static $operatorsSequence = [];

    public static function where(array $conditions, $operator = "")
    {
        foreach($conditions as $field => $value)
        {
            $oper = Operators::checkSpecialOperators($operator, $value);
            self::$whereStorage[] = "`{$field}` {$oper}";
        }
        $class = self::class;
        $classObject = new $class();
        $classObject->where = self::generateWhereString();
        if(empty($classObject->calledClass)){
            $classObject->calledClass = (string)get_called_class();
        }
        return $classObject;
    }

    public static function and()
    {
        self::$operatorsSequence[] = Operators::AND;
        $class = self::class;
        return new $class();
    }

    public static function or()
    {
        self::$operatorsSequence[] = Operators::OR;
        $class = self::class;
        return new $class();
    }

    protected static function generateWhereString()
    {
        $whereString = [];
        foreach(self::$whereStorage as $key => $item)
        {
            $whereString[] = $item . " " . @(!empty(self::$operatorsSequence) ? self::$operatorsSequence[$key] : "");
        }
        return implode(" ", $whereString);
    }

    public static function find($id)
    {}

    public static function all()
    {}

    public static function first()
    {}

    public static function last()
    {}
}
