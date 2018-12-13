<?php
class Path
{
    private $path;
    public function __construct($path) { $this->path = $path; }
    public function __invoke(...$args)
    {
        $path = $this->path;
        foreach ($args as $arg) {
            $path = preg_replace('#:-?([.\w]+)#', $arg, $path, 1);
        }
        return $path;
    }
}
