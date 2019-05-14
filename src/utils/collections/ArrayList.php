<?php

namespace Odin\utils\collections;

class ArrayList
{
    
    protected $list;
    protected $type;
    
    public function __construct(string $type) 
    {
        $this->type = $type;
        $this->list = [];
    }
    
    protected function checkType(object $o)
    {
        ($o instanceof $this->type) ? "" : die("Tipo inválido");
    }
    
    public function add(object $o)
    {
        $this->checkType($o);
        $this->list[] = $o;
    }
    
    public function addAt(int $index, object $element)
    {
        $this->checkType($element);
        $this->list[$index] = $element;
    }
    
    public function clear()
    {
        $this->list = [];
    }
    
    public function copy()
    {
        return new ArrayList($this->type);
    }
    
    public function contains(object $o)
    {
        $this->checkType($o);
        return array_search($o, $this->list) !== false;
    }
    
    public function get(int $index)
    {
        return $this->list[$index];
    }
    
    public function isEmpty(): bool
    {
        return empty($this->list);
    }
    
    public function remove(int $index)
    {
        unset($this->list[$index]);
    }
    
    public function set(int $index, object $element)
    {
        $this->checkType($element);
        $this->list[$index] = $element;
    }
    
    public function size(): int
    {
        return count($this->list);
    }
}
