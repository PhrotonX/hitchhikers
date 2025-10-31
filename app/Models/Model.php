<?php 

namespace App\Models;

use App\DataContext;

class Model implements \JsonSerializable{
    // public ?int $id = null;

    public $attributes = [];
    protected static $primary = "id";
    protected static $table;
    protected static $class;
    protected static $fillable;

    public function __get($key){
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value){
        $this->attributes[$key] = $value;
    }

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

        return static::fetch($exec, $results);
    }

    private static function fetch($exec, $results){
        try{
            $results->setFetchMode(\PDO::FETCH_ASSOC);
            $rows = $results->fetchAll();
            $objects = [];

            foreach ($rows as $row) {
                // var_dump($row);
                $object = new static();
                $object->fill($row);
                $objects[] = $object;
            }


            return $objects;

            // $results->setFetchMode(\PDO::FETCH_CLASS, static::class);
            // return $results->fetchAll();
        }catch(Exception $e){
            throw $e->getMessage();
        }
    }

    private function delete(){
        $dataContext = new DataContext();

        $query = "DELETE FROM ${static::table} WHERE ${static::primary} = $this->attributes['id']";

        $results = $dataContext->getPdo()->prepare($query);
        $exec = $results->execute();
    }

    private function fill(array $values){
        foreach ($values as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function save(){
        if($this->attribute['id'] == null){
            $this->onInsert();
        }else{
            $this->onEdit();
        }
    }

    private function getQuotedAttributes() : array{
        $quoted = [];
        foreach ($this->attributes as $key => $value) {
            $quoted[$key] = '"' . htmlspecialchars($value) . '"';
        }

        return $quoted;
    }

    private function onEdit(){
        $dataContext = new DataContext();

        // Add quotation marks to all values.
        $quoted = $this->getQuotedAttributes();

        $setQuery = [];
        foreach (static::fillable as $field) {
            $setQuery[] = "$field = $quoted[$field]";
        }

        // Create SQL query and prepare it.
        $query = "UPDATE ${static::table} SET ${implode(',', $setQuery)} WHERE $this->attributes['id'] = $quoted->id";
        $results = $dataContext->prepare($query);

        // Execute the query.
        $exec = $results->execute($parameters);

        return static::fetch($exec, $results);
    }

    private function onInsert(){
        $dataContext = new DataContext();

        // Add quotation marks to all values.
        // $quoted = array_map(fn($value) => '"' . $value . '"', $this->attributes);
        $quoted = $this->getQuotedAttributes();

        // Add ":" to all parameters.
        $parameterizedFields = array_map(fn($field) => ':' . $field, static::fillable);

        // Create SQL query and prepare it.
        // $query = "INSERT INTO ${static::table}(${implode(static::fillable)}) VALUES(${implode(',', $quoted)})";
        $query = "INSERT INTO ${static::table}(${implode(static::fillable)}) VALUES($parameterizedFields)";
        $results = $dataContext->prepare($query);

        // Set the parameter values and filter out attributes that are not part of fillable fields.
        $parameters = [];
        foreach (static::fillable as $field) {
            $parameters[":$field"] = $quoted[$field];
        }

        // Execute the query.
        $exec = $results->execute($parameters);

        return static::fetch($exec, $results);
    }

    public function jsonSerialize() : mixed{
        return $this->attributes;
    }
}