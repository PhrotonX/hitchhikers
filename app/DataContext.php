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
            $this->pdoConnect = new \PDO(env('DB_CONNECTION') . ":host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
                env("DB_USERNAME"), env("DB_PASSWORD"));
            $this->pdoConnect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    public function getPdo(){
        // var_dump(\PDO::getAvailableDrivers());
        return $this->pdoConnect;
    }
}
