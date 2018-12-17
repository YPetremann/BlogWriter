<?php
namespace User;

use \User\UserUserI,
    \Blog\UserBlogI;
use \User\UserUserT;
use \Blog\UserBlogT;
class User implements UserUserI, UserBlogI
{
    use UserUserT;
    use UserBlogT;

    protected $type;
    protected $id;
    protected $name;

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
