<?php
namespace App\Table;

use App\Model\User;
use Exception;
use PDO;

class UserTable extends Table{

    protected $table="user";
    protected $class=User::class;

    public function findByUsername(string $username)
    {
        $query=$this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $query->execute(['username'=>$username]);
        $query->setFetchMode(PDO::FETCH_CLASS,$this->class);
        $result=$query->fetch();
        if($result===false){
            throw new Exception("Utilisateur introuvable.");
        }
        return $result;
    }

}