<?php
namespace User;

class User implements \Blog\UserBlogI
{
    protected $type;
    protected $id;
    protected $name;
    use \Blog\UserBlogT;

    public function __construct($data=[])
    {
        $this->type = $data["type"] ?? "Guest";
        $this->id = $data["id"] ?? 0;
        $this->name = $data["name"] ?? "Utilisateur Anonyme";
    }
    public function __get(string $name)
    {
        $method = 'get_'.$name;
        return method_exists($this, $method) ? $this->$method() : null;
    }

    public function get_id() { return $this->id; }
    public function get_name() { return $this->name; }
    public function get_type() { return $this->type; }
}
