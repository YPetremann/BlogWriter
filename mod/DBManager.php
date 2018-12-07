<?php
class DBManager
{
    private static $db = null;
    public static function get()
    {
        return self::$db = self::$db ?? new PDO(
            'mysql:host=localhost;dbname=oc_yp_blogwriter;charset=utf8',
            'root',
            '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
