<?php
class Path implements GlobalS
{
    private $path;
    public function __construct($path, $unique = false) {
        $unique = ($unique)? (strpos($path,"?")?"&":"?")."v=".uniqid('', true):"";
        $this->path = self::urlprefix . $path . $unique;
    }
    public function __invoke(...$args)
    {
        $path = $this->path;
        foreach ($args as $arg) {
            $path = preg_replace('#:-?([.\w]+)#', $arg, $path, 1);
        }
        return $path;
    }
}
