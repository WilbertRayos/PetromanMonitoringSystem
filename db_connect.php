<?php 
    DEFINE('DB_USER','root');
    DEFINE('DB_PASSWORD','');

    $dsn = 'mysql:host=localhost;dbname=petroman';
    try{
        $db = new PDO($dsn, DB_USER,DB_PASSWORD);
    }catch (PDOException $e) {
        $error_msg = $e->getMessage();
        include('db_error.php');
        exit();
    }
?>