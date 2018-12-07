<?php
class ClassAutoload
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            switch (substr($class, -1)) {
                case 'I':
                case 'T':
                    $class = substr($class, 0, -1);
                    break;
            }
            $file = str_replace('\\', DIRECTORY_SEPARATOR, 'mod\\'.$class).'.php';
            if (file_exists($file) and is_file($file)) {
                require_once $file;
                return true;
            } else {
                return false;
            }
        });
    }
}
