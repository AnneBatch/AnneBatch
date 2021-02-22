<?php

/**
 * Class responsible for connecting to the database
 * It will be inherited by the SignUp class
 */
class DatabaseConnection
{
    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        try{
            $this->database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }
}
