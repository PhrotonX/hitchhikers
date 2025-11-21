<?php 

namespace App\Models;

use App\DataContext;
use Illuminate\Support\Facades\Log;

// A custom ORM or Object-Relational Mapping that avoids Laravel Eloquent and to use PHP PDO Database
// connections directly.
class Model implements \JsonSerializable{
    // public ?int $id = null;

    public $attributes = [];
    protected static $primary = "id";
    protected static $table;
    protected static $class;
    protected static $fillable;
    protected static $timestamps = true;

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

    public static function find($id){
        $query = "SELECT * FROM " . static::$table . " WHERE ". static::$primary ." = $id";
        return static::onSelect($query)[0] ?? null;
    }

    public static function where($field, $value){
        $query = "SELECT * FROM " . static::$table . " WHERE $field = $value";
        return static::onSelect($query);
    }

    public static function selectRawWhere($query){
        $statement = "SELECT * FROM " . static::$table . " WHERE $query";
        return static::onSelect($statement);
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

            if($objects == []){
                return response("Not Found", 404);
            }

            return $objects;

            // $results->setFetchMode(\PDO::FETCH_CLASS, static::class);
            // return $results->fetchAll();
        }catch(Exception $e){
            throw $e->getMessage();
        }
    }

    public function now(){
        $date = new \DateTimeImmutable();
        return $date->format("Y-m-d H:i:s");
    }

    public function delete(){
        $dataContext = new DataContext();

        $query = "DELETE FROM " .static::$table." WHERE " .static::$primary." = ".$this->attributes['id'];

        $results = $dataContext->getPdo()->prepare($query);
        $exec = $results->execute();

        return $exec;
    }

    public function fill(array $values){
        foreach ($values as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function save(){
        if(!isset($this->attributes->id)){
            $this->onInsert();
        }else{
            $this->onEdit();
        }
    }

    public function update(){
        $this->onEdit();
    }

    private function getQuotedAttributes() : array{
        $quoted = [];
        foreach ($this->attributes as $key => $value) {
            $quoted[$key] = '"' . htmlspecialchars($value) . '"';
        }

        return $quoted;
    }

    private function onEdit(){
        if(array_key_exists('updated_at', $this->attributes)){
            $this->attributes['updated_at'] = $this->now();
        }

        $dataContext = new DataContext();

        // Add quotation marks to all values.
        // $quoted = $this->getQuotedAttributes();

        Log::debug("Model.onEdit(): ");
        Log::debug($this->attributes);
        $setQuery = [];
        foreach (static::$fillable as $field) {
            if(isset($this->attributes[$field])){
                $setQuery[$field] = "$field = \"" . $this->attributes[$field] . '"';
            }
            // else{
            //     $setQuery[$field] = $this->attributes[$field];
            // }
                
        }

        // Create SQL query and prepare it.
        $query = "UPDATE ".static::$table." SET ".implode(',', $setQuery)." WHERE ".static::$primary." = ".$this->attributes['id'];
        Log::debug("Model.onEdit(): " . $query);
        $results = $dataContext->getPdo()->prepare($query);

        // Execute the query.
        $exec = $results->execute();

        if($exec){
            return static::where(static::$primary, $dataContext->getPdo()->lastInsertId());
        }
        // else{
        //     return response("404 Not Found", 404);
        // }
    }

    private function onInsert(){
        // if(array_key_exists('created_at', $this->attributes)){
        //     $this->attributes['created_at'] = $this->now();
        // }
        // if(array_key_exists('updated_at', $this->attributes)){
        //     $this->attributes['updated_at'] = $this->now();
        // }

        if(static::$timestamps){
            $this->attributes['created_at'] = $this->now();
            $this->attributes['updated_at'] = $this->now();
        }

        $dataContext = new DataContext();

        // Add quotation marks to all values.
        // $quoted = array_map(fn($value) => '"' . $value . '"', $this->attributes);
        // $quoted = $this->getQuotedAttributes();

        // Add ":" to all parameters.
        $parameterizedFields = array_map(fn($field) => ':' . $field, static::$fillable);

        // Create SQL query and prepare it.
        // $query = "INSERT INTO ${static::table}(${implode(static::fillable)}) VALUES(${implode(',', $quoted)})";
        $query = "INSERT INTO ".static::$table."(".implode(", ",static::$fillable).") VALUES(".implode(", ",$parameterizedFields).")";
        Log::debug("Model.onInsert(): " . $query);
        $results = $dataContext->getPdo()->prepare($query);

        // Set the parameter values and filter out attributes that are not part of fillable fields.
        $parameters = [];
        foreach (static::$fillable as $field) {
            if(isset($this->attributes[$field])){
                $parameters[":$field"] = $this->attributes[$field];
            }else{
                $parameters[":$field"] = null;
            }
        }

        Log::debug("Model.onInsert(): parameters");
        Log::debug($parameters);
        Log::debug("Model.onInsert(): attributes");
        Log::debug($this->attributes);

        // Execute the query.
        $exec = $results->execute($parameters);

        if($exec){
            return static::where(static::$primary, $dataContext->getPdo()->lastInsertId());
        }else{
            return response("Internal Server Error", 500);
        }
    }

    public function jsonSerialize() : mixed{
        return $this->attributes;
    }
}