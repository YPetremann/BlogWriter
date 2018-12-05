<?php
namespace User;

class User implements \Blog\UserBlogI
{
    use \Blog\UserBlogT;
    public function __get($name) {
        $method = 'get_'.$name;
        if(method_exists($this, $method)) {
            return $this->$method();
        } else {
            return "--";
        }
    }
}
