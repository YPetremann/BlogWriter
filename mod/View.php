<?php
class View
{
    private $data = [];
    public function __get(string $name) { return $this->data[$name] ?? ""; }
    public function __set(string $name, $value) { $this->data[$name] = $value; }
    public function __isset(string $name) { return isset($this->data[$name]); }
    public function __unset(string $name) { unset($this->data[$name]); }
    public function __call(string $name, array $args) { return $this->data[$name](...$args); }
}
