<?php 

namespace App\Models;

use App\DataContext;
use App\Models\Model;
use Illuminate\Support\Facades\Log;

class AssociativeModel extends Model{
    protected static $primary = [];

    public static function find($id, $secondId){
        $query = "SELECT * FROM " . static::$table . " WHERE ". static::$primary[0] ." = $id" . " AND " . static::$primary[1] . " = $secondId";
        return static::onSelect($query)[0] ?? null;
    }

    public function delete(){
        $dataContext = new DataContext();

        $query = "DELETE FROM " .static::$table." WHERE " . static::$primary[0] ." = " . $this->attributes[static::$primary[0]] . " AND " . static::$primary[1] . " = " . $this->attributes[static::$primary[1]];

        $results = $dataContext->getPdo()->prepare($query);
        $exec = $results->execute();

        return $exec;
    }

    private function onEdit(){
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
        $query = "UPDATE ".static::$table." SET ".implode(',', $setQuery)." WHERE " . static::$primary[0] ." = " . $this->attributes[static::$primary[0]] . " AND " . static::$primary[1] . " = " . $this->attributes[static::$primary[1]];
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

    public function save(){
        if(!isset($this->attributes[static::$primary[0]]) || $this->attributes[static::$primary[0]] === null){
            $this->onInsert();
        }else{
            $this->onEdit();
        }
    }

    private function onInsert(){
        $dataContext = new DataContext();

        $fields = [];
        $values = [];
        
        foreach (static::$fillable as $field) {
            if(isset($this->attributes[$field])){
                $fields[] = $field;
                $values[] = '"' . $this->attributes[$field] . '"';
            }
        }

        $query = "INSERT INTO ".static::$table." (".implode(',', $fields).") VALUES (".implode(',', $values).")";
        Log::debug("AssociativeModel.onInsert(): " . $query);
        
        $results = $dataContext->getPdo()->prepare($query);
        $exec = $results->execute();

        return $exec;
    }

    /**
     * @return string A pre-filled WHERE statement separated by AND for primary keys.
     */
    private function getFilledWhereId(){
        $query = "";
        for($i = 0; $i < count(static::$primary); $i++) {
            $query .= " " . static::$primary[$i] . " = " . $this->attributes[static::$primary[$i]] . " ";

            if($i < count(static::$primary) - 1){
                $query .= " AND ";
            }
        }

        return $query;
    }
}