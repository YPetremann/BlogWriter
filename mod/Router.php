<?php
class Router
{
    private $url;
    private $method;
    private $continue = true;
    public function __construct($url)
    {
        $this->url = trim($url, '/');
        $this->method = $_SERVER['REQUEST_METHOD'];
    }
    public function get($path, $function)
    {
        return $this->run("GET", $path, $function);
    }
    public function post($path, $function)
    {
        return $this->run("POST", $path, $function);
    }
    public function all($path, $function)
    {
        return $this->run(null, $path, $function);
    }
    public function default($function)
    {
        return $this->run(null, null, $function);
    }
    public function run($method, $path, $function)
    {
        ($path === null) ?: $path = trim($path, '/');
        if ($this->continue and ($method == null or $this->method == $method)) {
            $args = ($path === null) ? [] : $this->match($path);
            if ($args !== false) {
                $return = call_user_func_array($function, $args);
                ($return !== null) ?: $this->continue = false;
                return $this->continue;
            }
        }
        return false;
    }
    public function match($path)
    {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $path);
        $regex = "#^".$path."$#i";
        if (!preg_match($regex, $this->url, $matches)) {
            return false;
        }
        array_shift($matches);
        return $matches;
    }
}
