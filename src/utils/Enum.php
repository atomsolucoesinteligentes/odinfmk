<?php

namespace Odin\utils;

class Enum
{
    protected static $constCacheArray = [];

    public $key;

    public $value;

    public function __construct($enumValue)
    {
        if(!static::hasValue($enumValue)) {
            throw new \Exception("Erro");
        }

        $this->value = $enumValue;
        $this->key = static::getKey($enumValue);
    }

    protected static function getConstants(): array
    {
        $calledClass = get_called_class();

        if(!array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }

    protected static function getKey($value): string
    {
        return array_search($value, static::getConstants(), true);
    }

    protected static function getValues(): array
    {
        return array_values(static::getConstants());
    }

    protected static function hasValue($value, bool $strict = true): bool
    {
        $validValues = static::getValues();

        if($strict) {
            return in_array($value, $validValues);
        }

        return in_array((string) $value, array_map('strval', $validValues), true);
    }
}