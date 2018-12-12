<?php
class ClassAutoload
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $paths = ["mod\\"];
            switch (substr($class, -1)) {
                case 'C':
                    array_unshift($paths, "cfg\\" );
                case 'I':
                case 'T':
                    $class = substr($class, 0, -1);
                    break;
            }
            foreach ($paths as $path) {
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $path.$class).'.php';
                if (file_exists($file) and is_file($file)) {
                    require_once $file;
                    return true;
                }
            }
            return false;
        });
    }
}
