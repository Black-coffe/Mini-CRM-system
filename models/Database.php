<?php
// app/models/Database.php
namespace models;

class Database{
    private static $instance = null;
    private $conn;

    private function __construct(){
        $db_host = DB_HOST;
        $db_user = DB_USER;
        $db_pass = DB_PASS;
        $db_name = DB_NAME;

        try{
            $dsn = "mysql:host=$db_host;dbname=$db_name";
            $this->conn = new \PDO($dsn, $db_user, $db_pass);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e){
            echo "Connect failed: " . $e->getMessage();
        }
    }

    // возврат сам объект класса 'Database'
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // метод, который возвращает объект подключения к БД
    public function getConnection()
    {
        return $this->conn;
    }

}
