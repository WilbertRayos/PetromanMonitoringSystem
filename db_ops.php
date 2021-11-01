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