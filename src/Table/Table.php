<?php
namespace App\Table;

use Exception;
use PDO;

abstract class Table{

    protected $pdo;
    protected $table=null;
    protected $class=null;

    public function __construct(PDO $pdo)
    {
        if($this->table===null){
            throw new Exception("La classe " . get_class($this) . " n'a pas de propriété table");
        }
        if($this->class===null){
            throw new Exception("La classe " . get_class($this) . " n'a pas de propriété class");
        }
        $this->pdo=$pdo;
    }

    public function find(int $id)
    {
        $query=$this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id'=>$id]);
        $query->setFetchMode(PDO::FETCH_CLASS,$this->class);
        $result = $query->fetch();        
        if($result ===false){
            throw new Exception("Aucune catégorie ne correspond à l'id " . $id . " dans la table {$this->table}.");
        }
        return $result;
    }

    public function exists(string $field, $value, ?int $except=null):bool
    {
        $sql="SELECT COUNT(id) FROM {$this->table} WHERE $field=?";
        $params[]=$value;
        if($except !== null){
            $sql .= " AND id != ?";
            $params[]=$except;
        }
        $query=$this->pdo->prepare($sql);
        $query->execute($params);
        return (int)$query->fetch(PDO::FETCH_NUM)[0]>0;
    }

    public function all():array
    {
        return $this->pdo->query("SELECT * FROM {$this->table}",PDO::FETCH_CLASS,$this->class)->fetchAll();
    }

    public function delete(int $id):void
    {
        $query=$this->pdo->prepare("DELETE FROM {$this->table} WHERE id=?");
        $result=$query->execute([$id]);
        if($result===false){
            throw new Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        } 
    }

    public function create(array $data):int
    {
        $sqlFields=[];
        foreach($data as $key=>$value){
            $sqlFields[]="$key = :$key";
        }
        $query=$this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(", ",$sqlFields));
        $result=$query->execute($data);
        if($result===false){
            throw new Exception("Impossible de créer l'enregistrement dans la table {$this->table}");
        }
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $data, int $id):void
    {
        $sqlFields=[];
        foreach($data as $key=>$value){
            $sqlFields[]="$key = :$key";
        }
        $query=$this->pdo->prepare("UPDATE {$this->table} SET " . implode(", ",$sqlFields) . " WHERE id= :id");
        $result=$query->execute(array_merge($data,['id'=>$id]));
        if($result===false){
            throw new Exception("Impossible de modifier l'enregistrement dans la table {$this->table}");
        }
    }

}