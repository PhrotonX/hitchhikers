<?php

namespace App;

class DataContext
{
    private $pdoConnect;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        try{
            $this->pdoConnect = new \PDO(config('pdo.connection') . ":host=" . config('pdo.host') . ";dbname=" . config('pdo.database'),
                config("pdo.username"), config("pdo.password"));
        }catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    public function getPdo(){
        var_dump(\PDO::getAvailableDrivers());
        return $this->pdoConnect;
    }
}
