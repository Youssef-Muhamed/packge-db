<?php
//require "env.php";
namespace Packge\Dbwrapper;
class dbwrapper
{
    public $conneciton;
    public $query;
    public $sql;
    // Connection TO Database
    public function __construct($server,$username,$password,$dbname,$port = 3306)
    {
        $this->conneciton = mysqli_connect($server,$username,$password,$dbname,$port);
    }

    public function select($table,$column){
        $this->sql = "SELECT $column FROM $table";
        return $this;
    }
    public function where($column,$compair,$value){
        $value = (gettype($value) == 'string')? " '$value'" : "$value";
        $this->sql .= " WHERE $column $compair $value";

        return $this;
    }
    public function andWhere($column,$compair,$value){
        $this->sql .= " AND $column $compair '$value'";
        return $this;
    }
    public function orWhere($column,$compair,$value){
        $this->sql .= " OR $column $compair '$value'";
        return $this;
    }
    public function getAll(){
        $this->query();
        while ($row = mysqli_fetch_assoc($this->query)){
            $data[] = $row;
        }
        return $data;
    }
    public function getRow(){
        $this->query();
        $row = mysqli_fetch_assoc($this->query);
        return $row;
    }
    public function insert($table,$data){
        $row = $this->buildSql($data);
        $this->sql = "INSERT INTO $table SET $row";
        return $this;
    }

    public function update($table,$data){
        $row = $this->buildSql($data);
        $this->sql = "UPDATE $table SET $row";
        return $this;
    }

    public function delete($table){
        $this->sql = "DELETE FROM $table";
        return $this;
    }
    public function query(){
        $this->query = mysqli_query($this->conneciton,$this->sql);
    }
    public function buildSql($data){
        $row = "";
        foreach ($data as $key => $value){
            $value = (gettype($value) == 'string')? " '$value'" : "$value";
            $row.= "$key = $value ,";
        }
        $row = rtrim($row,",");
        return $row;
    }

    public function excu () {
        $this->query();
        if (mysqli_affected_rows($this->conneciton) > 0){
            return true;
        }else {
            return false;
        }
    }
}
//
//$dataa = new db();
//echo "<pre>";
////print_r($dataa->select("user","*")->where("id","=",1)->getRow());
//$data = [
//    "name" => "sayed",
//    "email" =>"n@n.com",
//    "password" =>111
//];
//
//$dataa->insert("user",$data)->excu();