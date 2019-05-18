<?php

namespace Odin\utils\collections;

class Queue
{

    protected $queue;
    protected $type;
    protected $limit;

    public function __construct(string $type) {
        $this->type = $type;
        $this->queue = [];
    }

    public function add(object $element)
    {
        if($this->size() <= $this->limit) {
            $this->checkType($element);
            $this->queue[] = $element;
        }
    }

    public function clear()
    {
        $this->queue = [];
    }

    protected function checkType(object $o)
    {
        ($o instanceof $this->type) ? "" : die("Tipo inválido");
    }

    public function contains(object $element): bool
    {
        return in_array($element, $this->queue);
    }

    public function dequeue()
    {
        $element = @reset($this->queue);
        $this->queue = array_shift($this->queue);
        return $element;
    }

    public function enqueue()
    {
        $element = @end($this->queue);
        $this->queue = array_pop($this->queue);
        return $element;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function peek()
    {
        return @reset($this->queue);
    }

    public function size(): int
    {
        return count($this->queue);
    }

    public function toJson()
    {
        return json_encode($this->queue, JSON_UNESCAPED_UNICODE);
    }

    public function trimExcess(int $limit)
    {
        $this->limit = $limit;
    }
}
