<?php
class Router
{
    private $url;
    private $method;
    private $process = [];
    private $continue = true;
    public function __construct($url)
    {
        $this->url($url);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }
    public function url($url) { $this->url = trim($url, '/'); }
    public function method($method) { $this->method = $method; }
    public function get($path, $function) { return $this->run("GET", $path, $function); }
    public function post($path, $function) { return $this->run("POST", $path, $function); }
    public function all($path, $function) { return $this->run(null, $path, $function); }
    public function default($function) { return $this->run(null, null, $function); }
    public function run($method, $path, $function)
    {
        array_push($this->process, ["method"=>$method, "path"=>$path, "function"=>$function]);
        return new Path($path);
    }
    public function process()
    {
        global $view;
        foreach ($this->process as $case) {
            ($case["path"] === null) ?: $case["path"] = trim($case["path"], '/');
            if ($this->continue and ($case["method"] == null or $this->method == $case["method"])) {
                $args = ($case["path"] === null) ? [] : $this->match($case["path"]);
                if ($args !== false) {
                    $view->message .= '<div class="neutral"><div class="fixer">'.($case["method"]===null?"ALL":$case["method"]).' /'.($case["path"]===null?"default":$case["path"]).'</div></div>';
                    $return = call_user_func_array($case["function"], $args);
                    if ($return === null) {
                        $this->continue = false;
                    }
                }
            }
        }
    }
    public function match($path)
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
