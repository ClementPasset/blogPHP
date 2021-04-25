<?php
namespace App;

use PDO;

class Connexion{

    public static function getPDO():PDO
    {
        return new PDO('mysql:dbname=monblog;host127.0.0.1','root','root',[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
    }

}