<?php
class DBManager implements DBC
{
    private static $db = null;
    public static function get()
    {
        return self::$db = self::$db ?? new PDO(
            'mysql:host='.self::dbhost.';dbname='.self::dbname.';charset=utf8',
            self::dbuser,
            self::dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
