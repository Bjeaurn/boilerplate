<?php

class DatabasePDO {

    // Hold an instance of the class
    private static $instance;
    private static $number_objects = 0;
    private static $queries;
    private $link;
    private $query;

    // A private constructor; prevents direct creation of object
    private function __construct() {
        $host = DB_HOST;
        $db = DB_DATABASE;
        $user = DB_USER;
        $password = DB_PASSWORD;
        $port = (int)DB_PORT;

        try{
            $this->link = new PDO("mysql:dbname=$db;host=$host;port=$port", $user, $password);
            //debugging
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            //use
            //$this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Database error: " . $e->getMessage());
        }
        self::$number_objects++;
        return $this->link;
    }

    // The start method
    public static function start() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public static function get_objects() {
        return self::$number_objects;
    }

    public static function get_queries() {
        return self::$queries;
    }

    public function prepare($query, $debug = false) {
        self::$queries++;
        $this->query = $query;
        if ($debug != false) {
            echo $this->query;
        }
        try{
            $result = $this->link->prepare($this->query);
            return $result;
        }catch(PDOException $e){
            $output = "<p><strong>Database error:</strong> " . $e->getMessage() . "<br />";
            $output .= "<strong>Last query:</strong> " . $this->query . "</p>";
            die($output);
        }
    }

    public function query($query, $debug = false) {
        self::$queries++;
        $this->query = $query;
        try {
            $this->result = $this->link->prepare($this->query);
            return $this->result->execute();
        } catch(PDOException $e) {
            $output = "<p><strong>Database error:</strong> " . $e->getMessage() . "<br />";
            $output .= "<strong>Last query:</strong> " . $this->query . "</p>";
            error_log($output);
            die($output);
        }
    }

    public function num_rows() {
        return $this->result->rowCount();
    }

    public function fetch_array() {
        return $this->result->fetch();
    }

    public function lastInsertId($name = null) {
        try{
            $result = $this->link->lastInsertId($name);
            return $result;
        }catch(PDOException $e){
            $output = "<p><strong>Database error:</strong> " . $e->getMessage() . "<br />";
            $output .= "<strong>Last query:</strong> " . $this->query . "</p>";
            error_log($output);
            die($output);
        }
    }

    public function close() {
        $this->link = null;
    }

    // Prevent users to clone the instance
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

}

?>
