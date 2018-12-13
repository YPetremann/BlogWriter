<?php
class Router
{
    private $url;
    private $method;
    private $process = [];
    public function __construct(string $url)
    {
        $this->url($url);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }
    public function url(string $url) { $this->url = trim($url, '/'); }
    public function method(string $method) { $this->method = $method; }
    public function get(string $path, callable $function) { return $this->run("GET", $path, $function); }
    public function post(string $path, callable $function) { return $this->run("POST", $path, $function); }
    public function all(string $path, callable $function) { return $this->run(null, $path, $function); }
    public function default(callable $function) { return $this->run(null, null, $function); }
    private function run($method, $path, $function)
    {
        array_push($this->process, ["method"=>$method, "path"=>$path, "function"=>$function]);
        return !$path?:new Path($path);
    }
    public function process()
    {
        global $view;
        $continue = true;
        foreach ($this->process as $case) {
            ($case["path"] === null) ?: $case["path"] = trim($case["path"], '/');
            if ($continue and ($case["method"] == null or $this->method == $case["method"])) {
                $args = ($case["path"] === null) ? [] : $this->match($case["path"]);
                if ($args !== false) {
                    //$view->message .= '<div class="neutral"><div class="fixer">'.($case["method"]===null?"ALL":$case["method"]).' /'.($case["path"]===null?"default":$case["path"]).'</div></div>';
                    $return = call_user_func_array($case["function"], $args);
                    if ($return === null) {
                        $continue = false;
                    }
                }
            }
        }
    }
    public function match(string $path)
    {
        $path = preg_replace('#:?\.\.\.#', '.*', $path);
        $path = preg_replace('#:-([\w]+)#', '[^/]+', $path);
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $path);
        $regex = "#^".$path."$#i";
        if (!preg_match($regex, $this->url, $matches)) {
            return false;
        }
        array_shift($matches);
        return $matches;
    }
}
