<?php

namespace Odin\database\operations;

final class Operators
{
    const EQUALS = "=";
    const DIFFERENT = "<>";
    const GREATER_THAN = ">";
    const LESS_THAN = "<";
    const GREATER_THAN_EQUALS_TO = ">=";
    const LESS_THAN_EQUALS_TO = "<=";
    const ALL = "ALL";
    const AND = "AND";
    const ANY = "ANY";
    const BETWEEN = "BETWEEN {0} AND {1}";
    const EXISTS = "EXISTS";
    const IN = "IN";
    const LIKE = "LIKE '%{}%'";
    const LIKE_LEFT = "LIKE '%{}'";
    const LIKE_RIGHT = "LIKE '{}%'";
    const NOT = "NOT";
    const NOT_IN = "NOT IN";
    const OR = "OR";
    const SOME = "SOME";

    public static function checkSpecialOperators(string $operator, $value)
    {
        if(strpos($operator, "LIKE") !== false){
            return str_replace("{}", $value, $operator);
        }else if(strpos($operator, "BETWEEN") !== false){
            $values = [self::detectDataType($value[0]), self::detectDataType($value[1])];
            return str_replace(["{0}", "{1}"], $values, $operator);
        }else{
            $formatedValue = self::detectDataType($value);
            return "{$operator} {$formatedValue}";
        }
    }

    protected static function detectDataType($value)
    {
        switch (gettype($value))
        {
            case 'integer':
                return intval($value);
            case 'double':
                return doubleval($value);
            case 'null':
                return 'NULL';
            case 'string':
                return "'{$value}'";
        }
    }
}
