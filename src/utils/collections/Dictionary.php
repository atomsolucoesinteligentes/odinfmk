<?php

namespace Odin\utils\collections;

class Dictionary 
{
    
    protected $dict;
    protected $keyType;
    protected $valueType;
    
    public function __construct(string $keyType, string $valueType) {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
        $this->dict = [];
    }
    
    protected function checkType($key = null, $value = null)
    {
        if($key !== null && gettype($key) != $this->keyType)
            die("Tipo da chave inválido");
        if($value !== null && !$value instanceof $this->keyType)
            die("Tipo do valor inválido");
    }
    
    public function add(string $key, string $value)
    {
        $this->checkType($key, $value);
        $this->dict[$key] = $value;
    }
    
    public function all()
    {
        return $this->dict;
    }
    
    public function clear()
    {
        $this->dict = [];
    }
    
    public function containsKey(string $key): bool
    {
        return array_key_exists($key, $this->dict);
    }
    
    public function containsValue(object $value): bool
    {
        return in_array($value, $this->dict);
    }
    
    public function get(string $key)
    {
        return $this->dict[$key];
    }
    
    public function getType()
    {
        return $this->valueType;
    }
    
    public function remove(string $key)
    {
        unset($this->dict[$key]);
    }
    
    public function toJson()
    {
        return json_encode($this->dict, JSON_UNESCAPED_UNICODE);
    }
}
