<?php

class User_Login {
    private $email;
    private $employee_id;
    private $employee_fName;
    private $employee_mName;
    private $employee_lName;
    private $employee_role;
    private $db;

    function __construct($email)
    {
        require_once('db_connect.php');

        $this->email = $email;
        $this->db = $db;
    }

    function validate_user_email() {
        try{
            $query_employee = "SELECT EXISTS(SELECT * FROM employees WHERE employee_email = :email)";
            $employee_statement = $this->db->prepare($query_employee);
            $employee_statement->bindParam(':email',$this->email, PDO::PARAM_STR);
            $employee_statement->execute();
            $employees = $employee_statement->fetch();
            $employee_statement->closeCursor();
    
            return $employees[0];
        } catch (PDOException $e) {
            $error_msg = $e;
            include('db_error.php');
        }
        
    }

    function validate_user_password() {
        try{
            $query_employee = "SELECT employee_id,employee_password FROM employees WHERE employee_email = :email";
            $employee_statement = $this->db->prepare($query_employee);
            $employee_statement->bindParam(':email',$this->email, PDO::PARAM_STR);
            $employee_statement->execute();
            $employees = $employee_statement->fetchAll();
            $employee_statement->closeCursor();
            
            $this->employee_id = $employees[0][0];
            return $employees[0][1];
        } catch(PDOException $e) {
            $error_msg = $e;
            include('db_error.php');
        }
    }

    function fetch_user_info() {
        try{
            $query_employee = "SELECT e.employee_fName, e.employee_mName, e.employee_lName, r.role_desc
                FROM employees e INNER JOIN roles r ON r.role_id = e.role_id WHERE e.employee_id = :employee_id;";
            $employee_statement = $this->db->prepare($query_employee);
            $employee_statement->bindParam(':employee_id',$this->employee_id, PDO::PARAM_INT);
            $employee_statement->execute();
            $employees = $employee_statement->fetchAll();
            $employee_statement->closeCursor();

            $this->employee_fName = $employees[0][0];
            $this->employee_mName = $employees[0][1];
            $this->employee_lName = $employees[0][2];
            $this->employee_role = $employees[0][3];
        } catch(PDOException $e) {
            $error_msg = $e;
            include('db_error.php');
        }
    }

    function getEmployeeID(){
        return $this->employee_id;
    }

    function getEmployeeFName(){
        return $this->employee_fName;
    }

    function getEmployeeMName(){
        return $this->employee_mName;
    }

    function getEmployeeLName(){
        return $this->employee_lName;
    }

    function getEmployeeRole(){
        return $this->employee_role;
    }
}

class Change_Password{
    private $email;
    private $newPassword;
    private $db;

    function __construct(){
        require_once('db_connect.php');

        $this->db = $db;
    }

    function validate_user_email(){
        try{
            $query_employee = "SELECT EXISTS(SELECT * FROM employees WHERE employee_email = :email)";
            $employee_statement = $this->db->prepare($query_employee);
            $employee_statement->bindParam(':email',$this->email, PDO::PARAM_STR);
            $employee_statement->execute();
            $employees = $employee_statement->fetch();
            $employee_statement->closeCursor();
    
            return $employees[0];
        } catch (PDOException $e) {
            $error_msg = $e;
            include('db_error.php');
        }
    }

    function change_user_password(){
        try{
            $query = "UPDATE employees SET employee_password = :newPassword WHERE employee_email = :employee_email";
            $stm = $this->db->prepare($query);
            $stm->bindValue(':newPassword',password_hash($this->newPassword, PASSWORD_DEFAULT));
            $stm->bindValue(':employee_email',$this->email);
            $execute_verdict = $stm->execute();
            $stm->closeCursor();

            return $execute_verdict;
        } catch (PDOException $e) {
            $error_msg = $e;
            include('db_error.php');
        }
    }

    function setEmployeeEmail($email){
        $this->email = $email;
    }

    function setEmployeeNewPassword($newPassword){
        $this->newPassword = $newPassword;
    }
}

class Update_User_Information{
    private $id;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $password;
    private $db;

    function __construct(){
        require_once('db_connect.php');

        $this->db = $db;
    }

    function updateUserInformation(){
        try{
            require_once('db_connect.php');
            $query = "UPDATE employees SET employee_fName = :fName, 
                    employee_mName = :mName, 
                    employee_lName = :lName , 
                    employee_email = :email, 
                    employee_password = :passwd WHERE employee_id = :id";
            $stm = $this->db->prepare($query);
            $stm->bindValue(':fName', $this->fName);
            $stm->bindValue(':mName', $this->mName);
            $stm->bindValue(':lName', $this->lName);
            $stm->bindValue(':email', $this->email);
            $stm->bindValue(':passwd', password_hash($this->password, PASSWORD_DEFAULT));
            $stm->bindValue(':id', $this->id);
            $s = $stm->execute();
            $stm->closeCursor();
            if(!$s){
              print_r($stm->errorInfo()[2]);
            }else{
                $_SESSION['employee_fName'] = $this->fName;
                $_SESSION['employee_mName'] = $this->mName;
                $_SESSION['employee_lName'] = $this->lName;
                $_SESSION['employee_email'] = $this->email;
                $_SESSION['employee_password'] = $this->password;
            }
        } catch(PDOException $e){
            $error_msg = $e;
            include('db_error.php');
        }
        
    }

    function setID($id){
        $this->id = $id;
    }

    function setFirstName($fName){
        $this->fName = $fName;
    }

    function setMiddleName($mName){
        $this->mName = $mName;
    }

    function setLastName($lName){
        $this->lName = $lName;
    }

    function setEmail($email){
        $this->email = $email;
    }

    function setPassword($password){
        $this->password = $password;
    }
}

class Fetch_All_Users{
    private $db;

    function __construct(){
        require_once('db_connect.php');
        $this->db = $db;
    }

   function fetchAllUsers(){
    try{
        $query = "SELECT e.employee_id, 
                        e.employee_fName, 
                        e.employee_mName, 
                        e.employee_lName, 
                        e.employee_email, 
                        r.role_desc FROM employees e INNER JOIN roles r ON r.role_id = e.role_id";
        $stm = $this->db->prepare($query);
        $stm->execute();
        $employees = $stm->fetchAll();
        $stm -> closeCursor();

        return $employees;
    }catch(PDOException $e){
        $error_msg = $e;
        include('db_error.php');
    }
   }
}