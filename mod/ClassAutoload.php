<?php
class ClassAutoload {
    public static function register() {
        spl_autoload_register(function($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, 'mod\\'.$class).'.php';
            echo $file;
            if (file_exists($file) and is_file($file)) {
                require $file;
                return true;
            } else {
                return false;
            }
        });
    }
}
