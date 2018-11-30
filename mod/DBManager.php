<?php
class DBManager
{
    protected function db_connect()
    {
        // Connexion à la base de données
        $db = new PDO('mysql:host=localhost;dbname=oc_yp_blogwriter;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $db;
    }
}
