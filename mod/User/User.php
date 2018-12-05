<?php
namespace User;

class User implements \Blog\UserBlogI
{
    protected $type;
    protected $id = 0;
    protected $name = "Utilisateur Anonyme";
    use \Blog\UserBlogT;
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
