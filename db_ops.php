<?php
require_once('db_connect.php');

class User_Login extends Dbh{
    private $email;
    private $employee_id;
    private $employee_fName;
    private $employee_mName;
    private $employee_lName;
    private $employee_role;

    function __construct($email)
    {
        $this->email = $email;
    }

    function validateUserEmail() {
        try{
            $query_employee = "SELECT EXISTS(SELECT * FROM employees WHERE employee_email = :email)";
            $employee_statement = $this->connect()->prepare($query_employee);
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
            $employee_statement = $this->connect()->prepare($query_employee);
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
            $employee_statement = $this->connect()->prepare($query_employee);
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

class Change_Password extends Dbh{
    private $email;
    private $newPassword;

    function __construct(){
    }

    function validateUserEmail(){
        try{
            $query_employee = "SELECT EXISTS(SELECT * FROM employees WHERE employee_email = :email)";
            $employee_statement = $this->connect()->prepare($query_employee);
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

    function changeUserPassword(){
        try{
            $query = "UPDATE employees SET employee_password = :newPassword WHERE employee_email = :employee_email";
            $stm = $this->connect()->prepare($query);
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

class Update_User_Information extends Dbh{
    private $id;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $password;


    function __construct(){

    }

    function updateUserInformation(){
        try{
            $query = "UPDATE employees SET employee_fName = :fName, 
                    employee_mName = :mName, 
                    employee_lName = :lName , 
                    employee_email = :email, 
                    employee_password = :passwd WHERE employee_id = :id";
            $stm = $this->connect()->prepare($query);
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

class Fetch_All_Users extends Dbh{

    function __construct(){
    }

   function fetchAllUsers(){
    try{
        $query = "SELECT e.employee_id, 
                        e.employee_fName, 
                        e.employee_mName, 
                        e.employee_lName, 
                        e.employee_email, 
                        r.role_desc FROM employees e INNER JOIN roles r ON r.role_id = e.role_id";
        $stm = $this->connect()->prepare($query);
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

class Add_New_Account extends Dbh{
    private $employee_fName;
    private $employee_mName;
    private $employee_lName;
    private $employee_email;
    private $employee_password;
    private $employee_role;


    function __construct(){
    
    }

    function addNewEmployee(){
        try{
            $query = "INSERT INTO employees (employee_fName, employee_mName, employee_lName, employee_email, employee_password, role_id) 
            VALUES (:fName, :mName, :lName, :email, :password, (SELECT role_id FROM roles WHERE role_desc = :role))";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':fName', $this->employee_fName);
            $stm->bindValue(':mName', $this->employee_mName);
            $stm->bindValue(':lName', $this->employee_lName);
            $stm->bindValue(':email', $this->employee_email);
            $stm->bindValue(':password', password_hash($this->employee_password,PASSWORD_DEFAULT));
            $stm->bindValue(':role', $this->employee_role);
            $stm->execute();
            $stm->closeCursor();
            
        }catch(PDOException $e){
            include('db_error.php');
            $error_msg = $e->getMessage();
        }
        

    }

    function setEmployee_fName($fName){
        $this->employee_fName = $fName;
    }

    function setEmployee_mName($mName){
        $this->employee_mName = $mName;
    }

    function setEmployee_lName($lName){
        $this->employee_lName = $lName;
    }

    function setEmployee_email($email){
        $this->employee_email = $email;
        echo $email;
    }

    function setEmployee_password($password){
        $this->employee_password = $password;
    }

    function setEmployee_role($role){
        $this->employee_role = $role;
    }
}

class Delete_Account extends Dbh{
    function deleteAccount($id){
        try{
            $query = "DELETE FROM employees WHERE employee_id = :id";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':id', $id);
            $stm->execute();
            $stm->closeCursor();
            
            //echo "<meta http-equiv='refresh' content='0'>";
        }catch(PDOException $e){
            $error_msg = $e;
            include('db_error.php');
        }
        
    }
}

class Update_Account extends Dbh{
    private $employee_id;
    private $employee_fName;
    private $employee_mName;
    private $employee_lName;
    private $employee_email;
    private $employee_role;

    function __construct(){

    }

    function updateAccount(){
        try{
            $query = "UPDATE employees 
            SET employee_fName = :fName, 
            employee_mName = :mName, 
            employee_lName = :lName, 
            employee_email = :email, 
            role_id = (SELECT role_id FROM roles WHERE role_desc = :role) 
            WHERE employee_id = :id";

            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':fName', $this->employee_fName);
            $stm->bindValue(':mName', $this->employee_mName);
            $stm->bindValue(':lName', $this->employee_lName);
            $stm->bindValue(':email', $this->employee_email);
            $stm->bindValue(':role', $this->employee_role);
            $stm->bindValue('id', $this->employee_id);
            $stm->execute();
            $stm->closeCursor();;
        }catch(PDOException $e){
            $error_msg = $e;
            include('db_error.php');
        }
        
    }

    function setEmployeeID($id){
        $this->employee_id = $id;
    }

    function setEmployeeFName($fName){
        $this->employee_fName = $fName;
    }

    function setEmployeeMName($mName){
        $this->employee_mName = $mName;
    }
    function setEmployeeLName($lName){
        $this->employee_lName = $lName;
    }

    function setEmployeeEmail($email){
        $this->employee_email = $email;
    }

    function setEmployeeRole($role){
        $this->employee_role = $role;
    }

}

class Add_New_Job_Order extends Dbh {
    private $job_order_number;
    private $client_name;
    private $representative;
    private $address;
    private $date;
    private $tin_number;
    private $project_location;
    private $terms_of_payment;
    private $mobilization;
    private $employee_id;

    function addNewJobOrder() {
        try {
            $query = "INSERT INTO job_order (job_order_number, client_name, representative, address, date, tin_number, 
            project_location, terms_of_payment, mobilization, employee_id) VALUES (:job_order_number, :client_name, 
            :representative, :address, :date, :tin_number, :project_location, :terms_of_payment, :mobilization, :employee_id)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':client_name', $this->client_name);
            $stm->bindValue(':representative', $this->representative);
            $stm->bindValue(':address', $this->address);
            $stm->bindValue(':date', $this->date);
            $stm->bindValue(':tin_number', $this->tin_number);
            $stm->bindValue(':project_location', $this->project_location);
            $stm->bindValue(':terms_of_payment', $this->terms_of_payment);
            $stm->bindValue(':mobilization', $this->mobilization);
            $stm->bindValue(':employee_id', $this->employee_id);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e){
            $error_msg = $e. "<br />";
            include('db_error.php');
        }
    }

    function addJobOrderItems($job_order_description, $job_order_unit,$job_order_quantity, $job_order_unit_price){
        try{
            $query = "INSERT INTO job_order_items (job_order_number, description, unit, quantity, unit_price) VALUES 
            (:job_order_number, :job_order_description, :job_order_unit, :job_order_quantity, :job_order_unit_price)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':job_order_description', $job_order_description);
            $stm->bindValue(':job_order_unit', $job_order_unit);
            $stm->bindValue(':job_order_quantity', $job_order_quantity);
            $stm->bindValue(':job_order_unit_price', $job_order_unit_price);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e."<br />";
        }
        
        
    }

    function fetchJobOrderID() {
        try{
            $query = "SELECT job_order_id FROM job_order WHERE job_order_number = :job_order_number AND client_name = :client_name AND 
            representative = :representative AND address = :address AND date = :date AND tin_number = :tin_number AND 
            project_location = :project_location AND terms_of_payment = :terms_of_payment AND mobilization = :mobilization AND 
            employee_id = :employee_id";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':client_name', $this->client_name);
            $stm->bindValue(':representative', $this->representative);
            $stm->bindValue(':address', $this->address);
            $stm->bindValue(':date', $this->date);
            $stm->bindValue(':tin_number', $this->tin_number);
            $stm->bindValue(':project_location', $this->project_location);
            $stm->bindValue(':terms_of_payment', $this->terms_of_payment);
            $stm->bindValue(':mobilization', $this->mobilization);
            $stm->bindValue(':employee_id', $this->employee_id);
            $stm->execute();
            $job_order_id = $stm->fetch();
            $stm->closeCursor();
            return $job_order_id;
        } catch(PDOException $e) {
         
        }
        

    }

    function setJobOrderNumber($job_order_number) {
        $this->job_order_number = $job_order_number;
    }

    function setClientName($client_name) {
        $this->client_name = $client_name;
    }

    function setRepresentative($representative) {
        $this->representative = $representative;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setDate($date) {
        $this->date = date("Y-m-d", strtotime($date) );
    }

    function setTinNumber($tin_number) {
        $this->tin_number = $tin_number;
    }
    
    function setProjectLocation($project_location) {
        $this->project_location = $project_location;
    }

    function setTermsOfPayment($terms_of_payment) {
        $this->terms_of_payment = $terms_of_payment;
    }

    function setMobilization($mobilization) {
        $this->mobilization = $mobilization;
    }

    function setEmployeeID($employee_id) {
        $this->employee_id = $employee_id;
    }


}


class Fetch_All_Job_Orders extends Dbh {
    private $arr_job_orders = [];

    function fetchAllJobOrders() {
        $query = "SELECT job_order_number, client_name, mobilization FROM job_order";
        $stm = $this->connect()->prepare($query);
        $stm->execute();
        $all_jo = $stm->fetchAll(PDO::FETCH_ASSOC);
        $stm->closeCursor();
        foreach ($all_jo as $jo_number) {
            $flt_total_jo_amount = (float) $this->fetchTotalJobOrderAmount($jo_number['job_order_number']) + (float) $jo_number['mobilization'];
            $current_phase_count = $this->countPhases($jo_number['job_order_number']);
            if ($current_phase_count === 4) {
                $current_phase = "Done";
            } else if ($current_phase_count === 0) {
                $current_phase = "Phase 1";
            } else {
                $current_phase = "Phase ".$current_phase_count;
            }

            array_push($this->arr_job_orders, array($jo_number['job_order_number'], $jo_number['client_name'], $flt_total_jo_amount, $current_phase));    
        }
        return $this->arr_job_orders;
        
    }

    function fetchTotalJobOrderAmount($jo_num) {
        $query = "SELECT SUM(quantity*unit_price) as result FROM job_order_items WHERE job_order_number = :jo_num";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':jo_num', $jo_num);
        $stm->execute();
        $total = $stm->fetch();
        $stm->closeCursor();
        
        return (float) $total[0];
    }

    function countPhases($job_num) {
        $query = "SELECT COUNT(job_order_number) current_phase FROM project_phases";
        $stm = $this->connect()->prepare($query);
        $stm->execute();
        $current_phase = $stm->fetch();
        $stm->closeCursor();

        return (int) $current_phase[0];
    }
}

class Fetch_Specific_Job_Order extends Dbh {
    private $job_order_number;
    function __construct($job_order_number) {
        $this->job_order_number = $job_order_number;
    }

    function fetchJobOrderInformation() {
        $query = "SELECT j.job_order_number, j.client_name, j.representative, j.address, j.date, j.tin_number, j.project_location, j.terms_of_payment, j.mobilization,
        CONCAT(e.employee_fName, e.employee_mName, e.employee_lName) as employee_name, SUM(i.quantity*i.unit_price) as jo_sum
        FROM job_order j 
        INNER JOIN employees e ON e.employee_id = j.employee_id
        INNER JOIN job_order_items i ON i.job_order_number = j.job_order_number
        WHERE j.job_order_number = :job_order_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':job_order_number', $this->job_order_number);
        $stm->execute();
        $jo_information = $stm->fetch(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $jo_information;
    }

    function fetchJobOrderItems() {
        $query = "SELECT description, unit, quantity, unit_price FROM job_order_items WHERE job_order_number = :job_order_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(":job_order_number", $this->job_order_number);
        $stm->execute();
        $all_jo_items = $stm->fetchAll(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $all_jo_items;
    }

    function checkExistingChecklist() {
        $query = "SELECT COUNT(job_order_number) FROM job_order_checklist WHERE job_order_number = :job_order_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':job_order_number', $this->job_order_number);
        $stm->execute();
        $jo_count = $stm->fetch(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $jo_count;
    }

    function fetchExistingChecklist() {
        try {
            $query = "SELECT o.control_number as or_cn, o.or_date, a.control_number as ar_cn, a.ar_date, w.control_number as ws_cn, w.ws_date, c.control_number as cr_cn, c.cr_date, d.control_number as dr_cn, d.dr_date, h.checklist_2303_2307, h.soa, h.total_materials_used
                FROM job_order_checklist h
                INNER JOIN or_ o ON o.job_order_number = h.job_order_number
                INNER JOIN ar a ON a.job_order_number = h.job_order_number
                INNER JOIN ws w ON w.job_order_number = h.job_order_number
                INNER JOIN cr c ON c.job_order_number = h.job_order_number
                INNER JOIN dr d ON d.job_order_number = h.job_order_number
                WHERE h.job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $jo_checklist = $stm->fetch();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e;
        }
        return $jo_checklist;
    }

    function updateExistingChecklist() {
        
    }

}

class Add_New_Checklist extends Dbh {
    private $job_order_number;
    private $or_control_number;
    private $or_date;
    private $ar_control_number;
    private $ar_date;
    private $ws_control_number;
    private $ws_date;
    private $cr_control_number;
    private $cr_date;
    private $dr_control_number;
    private $dr_date;
    private $checklist_2303;
    private $soa;
    private $total_materials_used;

    function __construct($job_order_number, 
                        $or_control_number, $or_date, $ar_control_number, $ar_date, 
                        $ws_control_number, $ws_date, $cr_control_number, $cr_date, 
                        $dr_control_number, $dr_date, 
                        $checklist_2303, $soa, $total_materials_used) {
        $this->job_order_number = $job_order_number;
        $this->or_control_number = $or_control_number;
        $this->or_date = $or_date;
        $this->ar_control_number = $ar_control_number;
        $this->ar_date = $ar_date;
        $this->ws_control_number = $ws_control_number;
        $this->ws_date = $ws_date;
        $this->cr_control_number = $cr_control_number;
        $this->cr_date = $cr_date;
        $this->dr_control_number = $dr_control_number;
        $this->dr_date = $dr_date;
        $this->checklist_2303 = $checklist_2303;
        $this->soa = $soa;
        $this->total_materials_used = $total_materials_used;
    }

    function addNewChecklist() {
        $this->addNewOR();
        $this->addNewAR();
        $this->addNewWS();
        $this->addNewCR();
        $this->addNewDR();
        $this->addNewChecklistInfo();
    }

    function addNewOR() {
        try{
            $query = "INSERT INTO or_ (job_order_number, control_number, or_date) 
            VALUES (:job_order_number, :control_number, :or_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':control_number', $this->or_control_number);
            $stm->bindValue(':or_date', $this->or_date);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
        
    }

    function addNewAR() {
        try{
            $query = "INSERT INTO ar (job_order_number, control_number, ar_date) 
            VALUES (:job_order_number, :control_number, :ar_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':control_number', $this->ar_control_number);
            $stm->bindValue(':ar_date', $this->ar_date);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function addNewWS() {
        try{
            $query = "INSERT INTO ws (job_order_number, control_number, ws_date) 
            VALUES (:job_order_number, :control_number, :ws_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':control_number', $this->ws_control_number);
            $stm->bindValue(':ws_date', $this->ws_date);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function addNewCR() {
        try{
            $query = "INSERT INTO cr (job_order_number, control_number, cr_date) 
            VALUES (:job_order_number, :control_number, :cr_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':control_number', $this->cr_control_number);
            $stm->bindValue(':cr_date', $this->cr_date);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function addNewDR() {
        try{
            $query = "INSERT INTO dr (job_order_number, control_number, dr_date) 
            VALUES (:job_order_number, :control_number, :dr_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':control_number', $this->dr_control_number);
            $stm->bindValue(':dr_date', $this->dr_date);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function addNewChecklistInfo() {
        try{
            $query = "INSERT INTO job_order_checklist (job_order_number, checklist_2303_2307, soa, total_materials_used) 
            VALUES (:job_order_number, :checklist_2303_2307, :soa, :total_materials_used)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':checklist_2303_2307', $this->checklist_2303);
            $stm->bindValue(':soa', $this->soa);
            $stm->bindValue(':total_materials_used', $this->total_materials_used);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
    }
}