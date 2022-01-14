<?php 

    // DEFINE('DB_USER','root');
    // DEFINE('DB_PASSWORD','');

    // $dsn = 'mysql:host=localhost;dbname=petroman';
    // try{
    //     $db = new PDO($dsn, DB_USER,DB_PASSWORD);
    // }catch (PDOException $e) {
    //     $error_msg = $e->getMessage();
    //     include('db_error.php');
    //     exit();
    // }

    class Dbh{

        private $DB_SERVER;
        private $DB_USER;
        private $DB_PASSWORD;
        private $DB_NAME;
    
        protected function connect(){
            $this->DB_SERVER = "localhost";
            $this->DB_USER = "root";
            $this->DB_PASSWORD = "";
            $this->DB_NAME = "petroman";
    
            // $this->DB_SERVER = "sql213.epizy.com";
            // $this->DB_USER = "epiz_30808673";
            // $this->DB_PASSWORD = "pmh72jA8cQ";
            // $this->DB_NAME = "epiz_30808673_petroman";
    
            try{
                $dsn = "mysql:host={$this->DB_SERVER};dbname={$this->DB_NAME}";
                $pdo = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }catch (PDOException $e) {
                $error_msg = $e->getMessage();
                include('db_error.php');
                exit();
            }
        }
    }
?> 

