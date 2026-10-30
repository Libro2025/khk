<?php

class dbConfig
{
    private $host ='localhost';
    private $username ='root';
    private $password ='';
    private $database ='khk';
    protected $conn;

    function __construct()
    {
        if(!isset($this->conn))
        {
            $this->conn = new mysqli($this->host,$this->username,$this->password,$this->database);
        }

        if(!$this->conn)
        {
            echo 'Nesupješno povezivanje s bazom podataka';
            exit;
        }

        return $this->conn;
    }
}
