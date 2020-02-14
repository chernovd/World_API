<?php

    class Database{

        // database credentials
        private $host;
        private $database;
        private $user;
        private $password;
        

        // get the database connection
        public function getConnection(){
            $this->host = "localhost";
            $this->database = "world";
            $this->user = "root";
            $this->password = "";

            //$conn = new mysqli($this->servername, $this->user, $this->password, $this->database);
            try
            {
                $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
                $this->conn->set_charset("utf8");
            }
            catch (mysqli_sql_exception $e) {
                echo "Connection error: " . $e->getMessage();
             }

            return $this->conn;
                
        }
    }

?>