<?php
class DBManager
{
    static private $db = null;
    static public function get()
    {
        if (self::$db == null){
            self::$db = new PDO('mysql:host=localhost;dbname=oc_yp_blogwriter;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        return self::$db;
    }
}
