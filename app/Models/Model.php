<?php 

namespace App\Models;

use App\DataContext;

class Model{
    public int $id;

    protected static $table;
    protected static $class;
    protected static $fillable;

    public static function all(){
        $query = "SELECT * FROM " . static::$table;
        return static::onSelect($query);
    }

    public static function where($field, $value){
        $query = "SELECT * FROM ${static::table} WHERE $field = $value";
        return static::onSelect($query);
    }

    private static function onSelect($query){
        $dataContext = new DataContext();

        $results = $dataContext->getPdo()->prepare($query);
        $exec = $results->execute();

        try{
            $results->setFetchMode(\PDO::FETCH_CLASS, static::class);
            return $results->fetchAll();
        }catch(Exception $e){
            throw $e->getMessage();
        }
    }
}