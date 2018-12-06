<?php
namespace User;

class User implements \Blog\UserBlogI
{
    public $type;
    public $id;
    public $name;
    use \Blog\UserBlogT;

    public function __construct($data)
    {
        $this->type = $data["type"] ?? "Guest";
        $this->id = $data["id"] ?? 0;
        $this->name = $data["name"] ?? "Utilisateur Anonyme";
    }
    public function __get($name) {
        $method = 'get_'.$name;
        if(method_exists($this, $method)) {
            return $this->$method();
        } else {
            return "--";
        }
    }

    public function get_id() {return $this->id;}
    public function get_name() {return $this->name;}
    public function get_type() {return $this->type;}
}
