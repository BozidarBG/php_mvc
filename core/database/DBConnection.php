<?php

namespace App\core\database;

use PDO;
use PDOException;

class DBConnection
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    protected $connectionError;

    protected $connection;

    public function __construct(){
        $this->setCredentials();
        $this->connection=$this->connect();
    }

    protected function setCredentials(){
        $db_config=getAppValue('database')['pdo'];
        $this->host=$db_config['host'];
        $this->dbname=$db_config['db_name'];
        $this->username=$db_config['db_username'];
        $this->password=$db_config['db_user_password'];

    }

    protected function connect(){
        $dsn="mysql:host=".$this->host.";dbname=".$this->dbname;
        $options=[
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try{
            $pdo=new PDO($dsn, $this->username, $this->password, $options);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        }catch(PDOException $e){
            $this->connectionError = $e->getMessage();
            echo $this->connectionError;
        }

    }
}