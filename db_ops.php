<?php
require_once('db_connect.php');

class User_Login extends Dbh{
    private $email;
    private $employee_id;
    private $employee_fName;
    private $employee_mName;
    private $employee_lName;
    private $employee_stats;
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
            $query_employee = "SELECT e.employee_fName, e.employee_mName, e.employee_lName, e.employee_stats, r.role_desc
                FROM employees e INNER JOIN roles r ON r.role_id = e.role_id WHERE e.employee_id = :employee_id;";
            $employee_statement = $this->connect()->prepare($query_employee);
            $employee_statement->bindParam(':employee_id',$this->employee_id, PDO::PARAM_INT);
            $employee_statement->execute();
            $employees = $employee_statement->fetchAll();
            $employee_statement->closeCursor();

            $this->employee_fName = $employees[0][0];
            $this->employee_mName = $employees[0][1];
            $this->employee_lName = $employees[0][2];
            $this->employee_stats = $employees[0][3];
            $this->employee_role = $employees[0][4];
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

    function getEmployeeStats(){
        return $this->employee_stats;
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
                        e.employee_stats,
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
        echo $this->employee_password;
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

    // function setEmployee_password($password){
    //     $this->employee_password = $password;
    // }

    function setEmployee_role($role){
        echo $role;
        if ($role == "admin") {
            $this->employee_password = "admin123";
        }else if ($role == "agent") {
            $this->employee_password = "user123";
        }
        $this->employee_role = $role;
    }
}

class Delete_Account extends Dbh{
    function deleteAccount($id){
        try{
            $query = "UPDATE employees SET employee_stats = 'inactive' WHERE employee_id = :id";
            // $query = "DELETE FROM employees WHERE employee_id = :id";
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
    private $contact_number;
    private $address;
    private $date;
    private $tin_number;
    private $project_location;
    private $terms_of_payment;
    private $mobilization;
    private $employee_id;

    function addNewJobOrder() {
        try {
            $query = "INSERT INTO job_order (job_order_number, client_name, representative, contact_number, address, date, tin_number, 
            project_location, terms_of_payment, mobilization, employee_id) VALUES (:job_order_number, :client_name, 
            :representative, :contact_number, :address, :date, :tin_number, :project_location, :terms_of_payment, :mobilization, :employee_id)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':client_name', $this->client_name);
            $stm->bindValue(':representative', $this->representative);
            $stm->bindValue(':contact_number', $this->contact_number);
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
            representative = :representative AND contact_number = :contact_number AND address = :address AND date = :date AND tin_number = :tin_number AND 
            project_location = :project_location AND terms_of_payment = :terms_of_payment AND mobilization = :mobilization AND 
            employee_id = :employee_id";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':client_name', $this->client_name);
            $stm->bindValue(':representative', $this->representative);
            $stm->bindValue(':contact_number', $this->contact_number);
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

    function setContactNumber($contactNumber) {
        $this->contact_number = $contactNumber;
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
            $highest_phase = $this->countPhases($jo_number['job_order_number']) + 1;
            if ($highest_phase === 5) {
                $current_phase = "Done";
            } else {
                $current_phase = "Phase ".$highest_phase;
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

    private function countPhases($job_num) {
        $query = "SELECT MAX(stage) FROM project_phases WHERE job_order_number = :job_order_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':job_order_number', $job_num);
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
        $query = "SELECT j.job_order_number, j.client_name, j.representative, j.contact_number, j.address, j.date, j.tin_number, j.project_location, j.terms_of_payment, j.mobilization,
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
        $query = "SELECT COUNT(job_order_number) AS total_number FROM job_order_checklist WHERE job_order_number = :job_order_number";
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

class Delete_Specific_Job_Order extends Dbh {
    private $job_order_number = "";

    function __construct($job_order_number){
        $this->job_order_number = $job_order_number;
    }

    function deleteJobOrder() {
        try {
            $query = "DELETE FROM job_order WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
        } catch (PDOException $e) {
            echo $e;
        }
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

class Update_Checklist extends Dbh {
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
    private $checklist_soa;
    private $checklist_materials;

    function __construct($job_order_number, $or_cn, $or_date, $ar_cn, $ar_date, $ws_cn, $ws_date, $cr_cn, $cr_date, $dr_cn, $dr_date, $checklist_2303, $soa, $materials) {
        $this->job_order_number = $job_order_number;
        $this->or_control_number = $or_cn;
        $this->or_date = $or_date;
        $this->ar_control_number = $ar_cn;
        $this->ar_date = $ar_date;
        $this->ws_control_number = $ws_cn;
        $this->ws_date = $ws_date;
        $this->cr_control_number = $cr_cn;
        $this->cr_date = $cr_date;
        $this->dr_control_number = $dr_cn;
        $this->dr_date = $dr_date;
        $this->checklist_2303 = $checklist_2303;
        $this->checklist_soa = $soa;
        $this->checklist_materials = $materials;
    }

    function updateChecklist() {
        try {
            $this->updateOR();
            $this->updateAR();
            $this->updateWS();
            $this->updateCR();
            $this->updateDR();
            $this->updateChecklistTable();
        } catch (PDOException $e) {
            $this->connect()->rollBack();
        }
    }

    function updateOR() {
        try {
            $query = "UPDATE or_ SET control_number = :new_control_number, or_date = :new_date WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->or_control_number);
            $stm->bindValue(':new_date', $this->or_date);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update OR";
            echo $e;
        }
    }

    function updateAR() {
        try {
            $query = "UPDATE ar SET control_number = :new_control_number, ar_date = :new_date WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->ar_control_number);
            $stm->bindValue(':new_date', $this->ar_date);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update AR";
            echo $e;
        }
    }

    function updateWS() {
        try {
            $query = "UPDATE ws SET control_number = :new_control_number, ws_date = :new_date WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->ws_control_number);
            $stm->bindValue(':new_date', $this->ws_date);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update WS";
            echo $e;
        }
    }

    function updateCR() {
        try {
            $query = "UPDATE cr SET control_number = :new_control_number, cr_date = :new_date WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->cr_control_number);
            $stm->bindValue(':new_date', $this->cr_date);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update CR";
            echo $e;
        }
    }

    function updateDR() {
        try {
            $query = "UPDATE dr SET control_number = :new_control_number, dr_date = :new_date WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->dr_control_number);
            $stm->bindValue(':new_date', $this->dr_date);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update DR";
            echo $e;
        }
    }

    function updateChecklistTable() {
        try {
            $query = "UPDATE job_order_checklist SET checklist_2303_2307 = :checklist_2303, soa = :soa, total_materials_used = :materials WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':checklist_2303', $this->checklist_2303);
            $stm->bindValue(':soa', $this->checklist_soa);
            $stm->bindValue(':materials', $this->checklist_materials);
            $stm->bindValue(':job_order_number', $this->job_order_number);

            $stm->execute();
            $stm->closeCursor();
        }catch (PDOException $e) {
            echo "wews".$e;
        }
        
    }
}

class Job_Order_Phases extends Dbh {
    private $job_order_number;
    
    function __construct($job_order_number) {
        $this->job_order_number = $job_order_number;
    }

    function fetchAllJOPhases() {
        try {
            $query = "SELECT * FROM project_phases WHERE job_order_number = :job_order_number ORDER BY stage";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $jo_phases = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();

            return $jo_phases;
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function addNewPhase($stage, $image) {
        try {
            $query = "INSERT INTO project_phases (job_order_number, stage, image) VALUES (:job_order_number, :stage, :image)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->bindValue(':stage', $stage);
            $stm->bindValue(':image', $image, PDO::PARAM_LOB);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
        
    }
}

class Update_Job_Order extends Dbh {
    private $job_order_number;

    function __construct($job_order_number) {
        echo $job_order_number;
        $this->job_order_number = $job_order_number;
    }

    function updateJobOrderInformation($nJob_order_number, $nClient_name, $nRepresentative, $nContact_number, $nAddress, $nDate, $nTin_number, $nProject_location, $nTerms_of_payment, $nMobilization) {
        $query = "UPDATE job_order SET job_order_number=:nJob_order_number, client_name =:nClient_name, 
                    representative=:nRepresentative, contact_number=:nContact_number, address=:nAddress, date=:nDate, tin_number=:nTin_number, 
                    project_location=:nProject_location, terms_of_payment=:nTerms_of_payment, mobilization=:nMobilization 
                    WHERE job_order_number = :job_order_number";
        
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':nJob_order_number',$nJob_order_number);
        $stm->bindValue(':nClient_name',$nClient_name);
        $stm->bindValue(':nRepresentative',$nRepresentative);
        $stm->bindValue(':nContact_number', $nContact_number);
        $stm->bindValue(':nAddress',$nAddress);
        $stm->bindValue(':nDate',$nDate);
        $stm->bindValue(':nTin_number',$nTin_number);
        $stm->bindValue(':nProject_location',$nProject_location);
        $stm->bindValue(':nTerms_of_payment',$nTerms_of_payment);
        $stm->bindValue(':nMobilization',$nMobilization);
        $stm->bindValue(':job_order_number', $this->job_order_number);

        $stm->execute();
        $stm->closeCursor();
    }

    function updateJobOrderItems($arr_jo_items) {
        foreach ($arr_jo_items as $jo_item) {
            if (!$this->checkJobOrderItem($jo_item[0], $jo_item[1], $jo_item[2], $jo_item[3]) > 0) {
                try {
                    $query = "INSERT INTO job_order_items (job_order_number, description, unit, quantity, unit_price) VALUES 
                            (:job_order_number, :description, :unit, :quantity, :unit_price)";
                    $stm = $this->connect()->prepare($query);
                    $stm->bindValue(':job_order_number', $this->job_order_number);
                    $stm->bindValue(':description', $jo_item[0]);
                    $stm->bindValue(':unit', $jo_item[1]);
                    $stm->bindValue(':quantity', $jo_item[2]);
                    $stm->bindValue(':unit_price', $jo_item[3]);
                    $stm->execute();
                    $stm->closeCursor();
                } catch(PDOException $e) {
                    echo $e;
                }   
            }
            else if ($this->checkJobOrderItem($jo_item[0], $jo_item[1], $jo_item[2], $jo_item[3]) == -1) {
                echo "Error Occured";
            }
        }
    }

    function checkJobOrderItem($description, $unit, $quantity, $unit_price) {
        try {
            $query = "SELECT EXISTS (SELECT job_order_number FROM job_order_items WHERE 
                    job_order_number = :job_order_number AND description = :description 
                    AND unit = :unit AND quantity = :quantity AND unit_price = :unit_price) AS verdict";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":job_order_number", $this->job_order_number);
            $stm->bindValue(":description", $description);
            $stm->bindValue(":unit", $unit);
            $stm->bindValue(":quantity", $quantity);
            $stm->bindValue(":unit_price", $unit_price);
            $stm->execute();
            $verdict = $stm->fetch();
            $stm->closeCursor();

            return $verdict['verdict'];

        } catch (PDOException $e) {
            echo $e;
            return -1;
        } 
    }

    function deleteJobOrderItem($arr_jo_items) {
        $existing_jo = $this->fetchJobOrders();
        foreach ($arr_jo_items as $jo_item) {
            foreach($existing_jo as $key => $val) {

                if ($jo_item[0] == $val['description'] && $jo_item[1] == $val['unit'] && $jo_item[2] == $val['quantity'] && $jo_item[3] == $val['unit_price']) {
                    unset($existing_jo[$key]);
                    break;
                }
            }
        }
        foreach($existing_jo as $jo) {
            $this->deleteFromJobOrderTable($jo['description'], $jo['unit'], $jo['quantity'], $jo['unit_price']);
        }
        // $query = "SELECT * FROM job_order_items WHERE job_order_number = :job_order_number";
        // $stm = $this->connect()->prepare($query);
        // $stm->bindValue(':job_order_number', $this->job_order_number);
        // $stm->execute();
        // $existing_job_order = $stm->fetchAll(PDO::FETCH_ASSOC);
        // $stm->closeCursor();

        // print_r($existing_job_order);
    }

    private function fetchJobOrders() {
        try {
            $query = "SELECT description, unit, quantity, unit_price FROM job_order_items WHERE job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $this->job_order_number);
            $stm->execute();
            $all_job_order = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            return $all_job_order;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function deleteFromJobOrderTable($description, $unit, $quantity, $unit_price) {
        $query = "DELETE FROM job_order_items WHERE job_order_number = :job_order_number AND description = :description AND unit = :unit AND quantity = :quantity and unit_price = :unit_price";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':job_order_number', $this->job_order_number);
        $stm->bindValue(':description', $description);
        $stm->bindValue(':unit', $unit);
        $stm->bindValue(':quantity', $quantity);
        $stm->bindValue(':unit_price', $unit_price);

        $stm->execute();
        $stm->closeCursor();
    }
    
}

class Finance_Job_Order extends Dbh {
    function fetchAllJobOrderFinance() {
        $query = "SELECT j.job_order_number, COALESCE(SUM(i.quantity*i.unit_price), 0)+j.mobilization AS remaining_balance, (CURDATE() - j.date) AS aging, COALESCE(SUM(i.quantity*i.unit_price)+j.mobilization, 0)-COALESCE(SUM(p.amount),0) AS status, CURDATE()-MAX(p.deposit_date) as last_payment
                    FROM job_order j
                    LEFT JOIN job_order_items i ON j.job_order_number = i.job_order_number
                    LEFT JOIN payments p ON j.job_order_number = p.job_order_number
                    GROUP BY j.job_order_number";
        $stm = $this->connect()->prepare($query);
        $stm->execute();
        $finance_job_order = $stm->fetchAll(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $finance_job_order;
    }

    function fetchSpecificJobOrderFinance($job_order_number) {
        echo $job_order_number;
        try {
            $query = "SELECT j.date AS date_created, COALESCE(SUM(i.quantity*i.unit_price), 0)+j.mobilization AS total_amount, j.terms_of_payment, COALESCE(SUM(i.quantity*i.unit_price)+j.mobilization, 0)-COALESCE(SUM(p.amount),0) AS remaining_balance
                        FROM job_order j
                        LEFT JOIN job_order_items i ON j.job_order_number = i.job_order_number
                        LEFT JOIN payments p ON j.job_order_number = p.job_order_number
                        WHERE j.job_order_number = :job_order_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $job_order_number);
            $stm->execute();
            $jo_finance_info = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();

            return $jo_finance_info[0];
        } catch(PDOException $e) {
            echo $e;
        }
    }

    function insertPayment($job_order_number, $amount, $bank, $reference_number, $deposit_date) {
        try {
            $query = "INSERT INTO payments (job_order_number, collectibles_type, amount, bank, reference_number, deposit_date) 
                        VALUES (:job_order_number, :collectibles_type, :amount, :bank, :reference_number, :deposit_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':job_order_number', $job_order_number);
            $stm->bindValue(':collectibles_type', "JO");
            $stm->bindValue(':amount', $amount);
            $stm->bindValue(':bank', $bank);
            $stm->bindValue(':reference_number', $reference_number);
            $stm->bindValue(':deposit_date', $deposit_date);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e;
        }
    }
}

class Create_New_Trading_Sales extends Dbh {
    private $order_form_number;
    private $client_name;
    private $representative;
    private $address;
    private $date;
    private $tin_number;
    private $terms_of_payment;
    private $employee_id;

    function addTradingSales() {
        try {
            $query = "INSERT INTO trading_sales (trading_sales_number, client_name, representative, address, trading_sales_date, tin_number, 
            terms_of_payment, employee_id) VALUES (:trading_sales_number, :client_name, 
            :representative, :address, :date, :tin_number, :terms_of_payment, :employee_id)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->bindValue(':client_name', $this->client_name);
            $stm->bindValue(':representative', $this->representative);
            $stm->bindValue(':address', $this->address);
            $stm->bindValue(':date', $this->date);
            $stm->bindValue(':tin_number', $this->tin_number);
            $stm->bindValue(':terms_of_payment', $this->terms_of_payment);
            $stm->bindValue(':employee_id', $this->employee_id);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e){
            $error_msg = $e. "<br />";
            include('db_error.php');
        }
    }

    function addTradingSalesItems($trading_sales_description, $trading_sales_unit,$trading_sales_quantity, $trading_sales_unit_price){
        try{
            $query = "INSERT INTO trading_sales_items (trading_sales_number, description, unit, quantity, unit_price) VALUES 
            (:trading_sales_number, :trading_sales_description, :trading_sales_unit, :trading_sales_quantity, :trading_sales_unit_price)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->bindValue(':trading_sales_description', $trading_sales_description);
            $stm->bindValue(':trading_sales_unit', $trading_sales_unit);
            $stm->bindValue(':trading_sales_quantity', $trading_sales_quantity);
            $stm->bindValue(':trading_sales_unit_price', $trading_sales_unit_price);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e."<br />";
        }
    }

    function fetchTradingSalesID() {
        try{
            $query = "SELECT trading_sales_id FROM trading_sales WHERE trading_sales_number = :trading_sales_number AND client_name = :client_name AND 
            representative = :representative AND address = :address AND trading_sales_date = :date AND tin_number = :tin_number AND 
            terms_of_payment = :terms_of_payment AND employee_id = :employee_id";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->bindValue(':client_name', $this->client_name);
            $stm->bindValue(':representative', $this->representative);
            $stm->bindValue(':address', $this->address);
            $stm->bindValue(':date', $this->date);
            $stm->bindValue(':tin_number', $this->tin_number);
            $stm->bindValue(':terms_of_payment', $this->terms_of_payment);
            $stm->bindValue(':employee_id', $this->employee_id);
            $stm->execute();
            $job_order_id = $stm->fetch();
            $stm->closeCursor();
            return $job_order_id;
        } catch(PDOException $e) {
         
        }

    }

    function setTradingSalesNumber($trading_sales_number) {
        $this->trading_sales_number = $trading_sales_number;
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
    
    function setTermsOfPayment($terms_of_payment) {
        $this->terms_of_payment = $terms_of_payment;
    }

    function setEmployeeID($employee_id) {
        $this->employee_id = $employee_id;
    }
}

class Fetch_All_Trading_Sales extends Dbh {
    private $arr_trading_sales = [];

    function fetchAllTradingSales() {
        $query = "SELECT t.trading_sales_number, t.client_name, 
                    CONCAT(e.employee_fName,' ', e.employee_mName, ' ', e.employee_lName) AS agent 
                    FROM trading_sales t INNER JOIN employees e ON t.employee_id = e.employee_id"; 
        $stm = $this->connect()->prepare($query);
        $stm->execute();
        $all_ts = $stm->fetchAll(PDO::FETCH_ASSOC);
        $stm->closeCursor();
        foreach ($all_ts as $ts_number) {
            $flt_total_ts_amount = (float) $this->fetchTotalTradingSalesAmount($ts_number['trading_sales_number']);

            array_push($this->arr_trading_sales, array($ts_number['trading_sales_number'], $ts_number['client_name'], $flt_total_ts_amount, $ts_number['agent']));    
        }
        return $this->arr_trading_sales;
        
    }

    function fetchTotalTradingSalesAmount($ts_num) {
        $query = "SELECT SUM(quantity*unit_price) as result FROM trading_sales_items WHERE trading_sales_number = :ts_num";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':ts_num', $ts_num);
        $stm->execute();
        $total = $stm->fetch();
        $stm->closeCursor();
        
        return (float) $total[0];
    }

}

class Fetch_Specific_Trading_Sales extends Dbh {
    private $trading_sales_number;
    function __construct($trading_sales_number) {
        $this->trading_sales_number = $trading_sales_number;
    }

    function fetchTradingSalesInformation() {
        $query = "SELECT t.trading_sales_number, t.client_name, t.representative, t.address, t.trading_sales_date, t.tin_number, t.terms_of_payment, 
        CONCAT(e.employee_fName, e.employee_mName, e.employee_lName) as employee_name, SUM(i.quantity*i.unit_price) as ts_sum
        FROM trading_sales t
        INNER JOIN employees e ON e.employee_id = t.employee_id
        INNER JOIN trading_sales_items i ON i.trading_sales_number = t.trading_sales_number
        WHERE t.trading_sales_number = :trading_sales_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
        $stm->execute();
        $ts_information = $stm->fetch(PDO::FETCH_ASSOC);
        $stm->closeCursor();
        return $ts_information;
    }

    function fetchtradingSalesItems() {
        $query = "SELECT description, unit, quantity, unit_price FROM trading_sales_items WHERE trading_sales_number = :trading_sales_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(":trading_sales_number", $this->trading_sales_number);
        $stm->execute();
        $all_ts_items = $stm->fetchAll(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $all_ts_items;
    }

    function checkExistingChecklist() {
        $query = "SELECT COUNT(trading_sales_number) AS total_number FROM trading_sales_checklist WHERE trading_sales_number = :trading_sales_number";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
        $stm->execute();
        $ts_count = $stm->fetch(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $ts_count;
    }

    function fetchExistingChecklist() {
        try {
            $query = "SELECT o.control_number AS or_cn, o.or_date, a.control_number AS ar_cn, a.ar_date, w.control_number AS ws_cn, w.ws_date, c.control_number AS cr_cn, c.cr_date, d.control_number AS dr_cn, d.dr_date, h.checklist_2303_2307, h.soa, h.total_materials_used
                FROM trading_sales_checklist h
                INNER JOIN trading_sales_or o ON o.trading_sales_number = h.trading_sales_number
                INNER JOIN trading_sales_ar a ON a.trading_sales_number = h.trading_sales_number
                INNER JOIN trading_sales_ws w ON w.trading_sales_number = h.trading_sales_number
                INNER JOIN trading_sales_cr c ON c.trading_sales_number = h.trading_sales_number
                INNER JOIN trading_sales_dr d ON d.trading_sales_number = h.trading_sales_number
                WHERE h.trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $ts_checklist = $stm->fetch();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e;
        }
        return $ts_checklist;
    }

    function updateExistingChecklist() {
        
    }

}

class Update_Trading_Sales extends Dbh {
    private $Trading_Sales_number;

    function __construct($trading_sales_number) {
        echo $trading_sales_number;
        $this->trading_sales_number = $trading_sales_number;
    }

    function updateTradingSalesInformation($nTrading_sales_number, $nClient_name, $nRepresentative, $nAddress, $nTrading_sales_date, $nTin_number, $nTerms_of_payment) {
        $query = "UPDATE trading_sales SET trading_sales_number=:nTrading_sales_number, client_name =:nClient_name, 
                    representative=:nRepresentative, address=:nAddress, trading_sales_date=:nTrading_sales_date, tin_number=:nTin_number, 
                    terms_of_payment=:nTerms_of_payment
                    WHERE trading_sales_number = :trading_sales_number";
        
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':nTrading_sales_number',$nTrading_sales_number);
        $stm->bindValue(':nClient_name',$nClient_name);
        $stm->bindValue(':nRepresentative',$nRepresentative);
        $stm->bindValue(':nAddress',$nAddress);
        $stm->bindValue(':nTrading_sales_date',$nTrading_sales_date);
        $stm->bindValue(':nTin_number',$nTin_number);
        $stm->bindValue(':nTerms_of_payment',$nTerms_of_payment);
        $stm->bindValue(':trading_sales_number', $this->trading_sales_number);

        $stm->execute();
        $stm->closeCursor();
    }

    function updateTradingSalesItems($arr_ts_items) {
        foreach ($arr_ts_items as $ts_item) {
            if (!$this->checkTradingSalesItem($ts_item[0], $ts_item[1], $ts_item[2], $ts_item[3]) > 0) {
                try {
                    $query = "INSERT INTO trading_sales_items (trading_sales_number, description, unit, quantity, unit_price) VALUES 
                            (:trading_sales_number, :description, :unit, :quantity, :unit_price)";
                    $stm = $this->connect()->prepare($query);
                    $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
                    $stm->bindValue(':description', $ts_item[0]);
                    $stm->bindValue(':unit', $ts_item[1]);
                    $stm->bindValue(':quantity', $ts_item[2]);
                    $stm->bindValue(':unit_price', $ts_item[3]);
                    $stm->execute();
                    $stm->closeCursor();
                } catch(PDOException $e) {
                    echo $e;
                }   
            }
            else if ($this->checkTradingSalesItem($ts_item[0], $ts_item[1], $ts_item[2], $ts_item[3]) == -1) {
                echo "Error Occured";
            }
        }
    }

    function checkTradingSalesItem($description, $unit, $quantity, $unit_price) {
        try {
            $query = "SELECT EXISTS (SELECT trading_sales_number FROM trading_sales_items WHERE 
                    trading_sales_number = :trading_sales_number AND description = :description 
                    AND unit = :unit AND quantity = :quantity AND unit_price = :unit_price) AS verdict";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":trading_sales_number", $this->trading_sales_number);
            $stm->bindValue(":description", $description);
            $stm->bindValue(":unit", $unit);
            $stm->bindValue(":quantity", $quantity);
            $stm->bindValue(":unit_price", $unit_price);
            $stm->execute();
            $verdict = $stm->fetch();
            $stm->closeCursor();

            return $verdict['verdict'];

        } catch (PDOException $e) {
            echo $e;
            return -1;
        } 
    }

    function deleteTradingSalesItem($arr_ts_items) {
        $existing_ts = $this->fetchTradingSales();
        foreach ($arr_ts_items as $ts_item) {
            foreach($existing_ts as $key => $val) {

                if ($ts_item[0] == $val['description'] && $ts_item[1] == $val['unit'] && $ts_item[2] == $val['quantity'] && $ts_item[3] == $val['unit_price']) {
                    unset($existing_ts[$key]);
                    break;
                }
            }
        }
        foreach($existing_ts as $ts) {
            $this->deleteFromTradingSalesTable($ts['description'], $ts['unit'], $ts['quantity'], $ts['unit_price']);
        }
        // $query = "SELECT * FROM job_order_items WHERE job_order_number = :job_order_number";
        // $stm = $this->connect()->prepare($query);
        // $stm->bindValue(':job_order_number', $this->job_order_number);
        // $stm->execute();
        // $existing_job_order = $stm->fetchAll(PDO::FETCH_ASSOC);
        // $stm->closeCursor();

        // print_r($existing_job_order);
    }

    private function fetchTradingSales() {
        try {
            $query = "SELECT description, unit, quantity, unit_price FROM trading_sales_items WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $all_trading_sales = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            return $all_trading_sales;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function deleteFromTradingSalesTable($description, $unit, $quantity, $unit_price) {
        $query = "DELETE FROM trading_sales_items WHERE trading_sales_number = :trading_sales_number AND description = :description AND unit = :unit AND quantity = :quantity and unit_price = :unit_price";
        $stm = $this->connect()->prepare($query);
        $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
        $stm->bindValue(':description', $description);
        $stm->bindValue(':unit', $unit);
        $stm->bindValue(':quantity', $quantity);
        $stm->bindValue(':unit_price', $unit_price);

        $stm->execute();
        $stm->closeCursor();
    }
    
}

class Add_New_Trading_Sales_Checklist extends Dbh {
    private $trading_sales_number;
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

    function __construct($trading_sales_number, 
                        $or_control_number, $or_date, $ar_control_number, $ar_date, 
                        $ws_control_number, $ws_date, $cr_control_number, $cr_date, 
                        $dr_control_number, $dr_date, 
                        $checklist_2303, $soa, $total_materials_used) {
        $this->trading_sales_number = $trading_sales_number;
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
            $query = "INSERT INTO trading_sales_or (trading_sales_number, control_number, or_date) 
            VALUES (:trading_sales_number, :control_number, :or_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
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
            $query = "INSERT INTO trading_sales_ar (trading_sales_number, control_number, ar_date) 
            VALUES (:trading_sales_number, :control_number, :ar_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
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
            $query = "INSERT INTO trading_sales_ws (trading_sales_number, control_number, ws_date) 
            VALUES (:trading_sales_number, :control_number, :ws_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
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
            $query = "INSERT INTO trading_sales_cr (trading_sales_number, control_number, cr_date) 
            VALUES (:trading_sales_number, :control_number, :cr_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
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
            $query = "INSERT INTO trading_sales_dr (trading_sales_number, control_number, dr_date) 
            VALUES (:trading_sales_number, :control_number, :dr_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
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
            $query = "INSERT INTO trading_sales_checklist (trading_sales_number, checklist_2303_2307, soa, total_materials_used) 
            VALUES (:trading_sales_number, :checklist_2303_2307, :soa, :total_materials_used)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
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

class Update_Trading_Sales_Checklist extends Dbh {
    private $trading_sales_number;
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
    private $checklist_soa;
    private $checklist_materials;

    function __construct($trading_sales_number, $or_cn, $or_date, $ar_cn, $ar_date, $ws_cn, $ws_date, $cr_cn, $cr_date, $dr_cn, $dr_date, $checklist_2303, $soa, $materials) {
        $this->trading_sales_number = $trading_sales_number;
        $this->or_control_number = $or_cn;
        $this->or_date = $or_date;
        $this->ar_control_number = $ar_cn;
        $this->ar_date = $ar_date;
        $this->ws_control_number = $ws_cn;
        $this->ws_date = $ws_date;
        $this->cr_control_number = $cr_cn;
        $this->cr_date = $cr_date;
        $this->dr_control_number = $dr_cn;
        $this->dr_date = $dr_date;
        $this->checklist_2303 = $checklist_2303;
        $this->checklist_soa = $soa;
        $this->checklist_materials = $materials;
    }

    function updateChecklist() {
        try {
            $this->updateOR();
            $this->updateAR();
            $this->updateWS();
            $this->updateCR();
            $this->updateDR();
            $this->updateChecklistTable();
        } catch (PDOException $e) {
            $this->connect()->rollBack();
        }
    }

    function updateOR() {
        try {
            $query = "UPDATE trading_sales_or SET control_number = :new_control_number, or_date = :new_date WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->or_control_number);
            $stm->bindValue(':new_date', $this->or_date);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update OR";
            echo $e;
        }
    }

    function updateAR() {
        try {
            $query = "UPDATE trading_sales_ar SET control_number = :new_control_number, ar_date = :new_date WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->ar_control_number);
            $stm->bindValue(':new_date', $this->ar_date);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update AR";
            echo $e;
        }
    }

    function updateWS() {
        try {
            $query = "UPDATE trading_sales_ws SET control_number = :new_control_number, ws_date = :new_date WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->ws_control_number);
            $stm->bindValue(':new_date', $this->ws_date);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update WS";
            echo $e;
        }
    }

    function updateCR() {
        try {
            $query = "UPDATE trading_sales_cr SET control_number = :new_control_number, cr_date = :new_date WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->cr_control_number);
            $stm->bindValue(':new_date', $this->cr_date);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update CR";
            echo $e;
        }
    }

    function updateDR() {
        try {
            $query = "UPDATE trading_sales_dr SET control_number = :new_control_number, dr_date = :new_date WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':new_control_number', $this->dr_control_number);
            $stm->bindValue(':new_date', $this->dr_date);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo "Error in update DR";
            echo $e;
        }
    }

    function updateChecklistTable() {
        try {
            $query = "UPDATE trading_sales_checklist SET checklist_2303_2307 = :checklist_2303, soa = :soa, total_materials_used = :materials WHERE trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':checklist_2303', $this->checklist_2303);
            $stm->bindValue(':soa', $this->checklist_soa);
            $stm->bindValue(':materials', $this->checklist_materials);
            $stm->bindValue(':trading_sales_number', $this->trading_sales_number);
            $stm->execute();
            $stm->closeCursor();
        }catch (PDOException $e) {
            echo "wews".$e;
        }
        
    }
}

class Finance_Trading_Sales extends Dbh {
    function fetchAllTradingSalesFinance() {
        $query = "SELECT t.trading_sales_number,sum(i.quantity*i.unit_price)-COALESCE(amount,0) AS remaining_balance, (CURDATE() - t.trading_sales_date) AS aging
                    FROM trading_sales t
                    LEFT JOIN trading_sales_items i ON i.trading_sales_number = t.trading_sales_number
                    LEFT JOIN (SELECT p.trading_sales_number, SUM(p.amount) AS amount FROM trading_sales_payment p 
                    GROUP BY p.trading_sales_number) p ON p.trading_sales_number = t.trading_sales_number
                    GROUP BY trading_sales_number";
        $stm = $this->connect()->prepare($query);
        $stm->execute();
        $finance_trading_sales = $stm->fetchAll(PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $finance_trading_sales;
    }

    function fetchSpecificTradingSalesFinance($trading_sales_number) {
        echo $trading_sales_number;
        try {
            $query = "SELECT t.trading_sales_date AS date_created, COALESCE(SUM(i.quantity*i.unit_price), 0) AS total_amount, t.terms_of_payment, COALESCE(SUM(i.quantity*i.unit_price), 0)-COALESCE(amount,0) AS remaining_balance
                        FROM trading_sales t
                        LEFT JOIN trading_sales_items i ON t.trading_sales_number = i.trading_sales_number
                        LEFT JOIN (SELECT trading_sales_number, SUM(amount) AS amount FROM trading_sales_payment GROUP BY trading_sales_number)  p ON p.trading_sales_number = t.trading_sales_number
                        WHERE t.trading_sales_number = :trading_sales_number";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $trading_sales_number);
            $stm->execute();
            $ts_finance_info = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            return $ts_finance_info[0];
        } catch(PDOException $e) {
            echo $e;
        }
    }

    function insertPayment($trading_sales_number, $amount, $bank, $reference_number, $deposit_date) {
        try {
            echo "ewewew";
            $query = "INSERT INTO trading_sales_payment (trading_sales_number, amount, bank, reference_number, deposit_date) 
                        VALUES (:trading_sales_number, :amount, :bank, :reference_number, :deposit_date)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':trading_sales_number', $trading_sales_number);
            $stm->bindValue(':amount', $amount);
            $stm->bindValue(':bank', $bank);
            $stm->bindValue(':reference_number', $reference_number);
            $stm->bindValue(':deposit_date', $deposit_date);
            $stm->execute();
            $stm->closeCursor();
            
        } catch(PDOException $e) {
            echo $e;
        }
    }
}

class Insert_Reset_Unique extends Dbh {
    private $email;
    private $code;

    function __construct($email)
    {
        $this->email = $email;
        $this->code = uniqid(true);
    }

    function check_email_exists() {
        try {
            $query = "SELECT EXISTS(SELECT * FROM employees WHERE employee_email = :email) AS cnt";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":email", $this->email);
            $stm->execute();
            $employee_exists = $stm->fetch(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            return $employee_exists['cnt'];
        } catch(PDOException $e) {
            echo $e;
        }
    }


    function insert_new_unique()
    {
        try {
            $query = "INSERT INTO reset_password (code, email) VALUES (:code, :email)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':code', $this->code);
            $stm->bindValue(':email', $this->email);
            $stm->execute();
            $stm->closeCursor();

        }catch(PDOException $e) {
            echo $e;
        }
    }

    function getCode() {
        return $this->code;
    }
}

class Fetch_Particular_Email extends Dbh {
    private $code;

    function __construct($code)
    {
        $this->code = $code;
    }

    function fetch_email() {
        try{
            $query = "SELECT email FROM reset_password WHERE code = :code";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":code", $this->code);
            $stm->execute();
            $email = $stm->fetch(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            return $email['email'];
        }catch(PDOException $e) {
            echo "error";
        }
    }

}

class Update_Employee_Password extends Dbh {
    private $password;
    function __construct($password)
    {   
        $this->password = $password;
    }

    function update_password() {
        try {
            $query = "UPDATE employees SET employee_password = :password";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":password", password_hash($this->password, PASSWORD_DEFAULT));
            $stm->execute();
            $stm->closeCursor();
            return 0;
        } catch(PDOException $e) {
            return -1;
        }
    }

    function delete_code($code, $email) {
        try {
            $query = "DELETE FROM reset_password WHERE code = :code AND email = :email";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":code", $code);
            $stm->bindValue(":email", $email);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e) {

        }
    }

}


class Fetch_Warehouse_Products extends Dbh {
    private $products;

    function __construct()
    {
        $this->products = array();
    }

    function fetchProductFromDatabase() {
        $query = "SELECT product_desc FROM products ORDER BY product_desc ASC";
        $stm = $this->connect()->prepare($query);
        $stm->execute();
        $temp = $stm->fetchAll();
        $stm->closeCursor();

        foreach($temp as $item) {
            array_push($this->products,$item[0]);
        }
    }

    function getProducts() {
        return $this->products;
    }
}

class Process_Warehouse_Products extends Dbh {
    private $control_number;
    private $process_date;
    private $person_name;
    private $arr_products;
    private $activity;

    function __construct($activity, $control_number, $date, $person_name, $arr_products)
    {
        $this->activity = $activity;
        $this->control_number = $control_number;
        $this->process_date = date("Y-m-d", strtotime($date));
        $this->person_name = $person_name;
        $this->arr_products = $arr_products;
    }

    function fetchPreviousEnd($product) {
        try{
            $query = "SELECT ending_qty FROM warehouse WHERE product_id=(SELECT product_id FROM products WHERE product_desc = :product_desc) ORDER BY operation_date DESC LIMIT 1";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(':product_desc', $product);
            $stm->execute();
            $ending_qty = $stm->fetch(PDO::FETCH_ASSOC);
            $stm->closeCursor();

            return $ending_qty["ending_qty"];
        }catch(PDOException $e) {
            echo $e;
        }
    }

    function insertProduct($product_desc, $beginning_qty, $ending_qty) {
        echo $this->process_date;
        try {
            $query = "INSERT INTO warehouse (product_id, control_number, person_name, operation_date, beginning_qty, ending_qty) 
                        VALUES ((SELECT product_id FROM products WHERE product_desc = :product_desc), :control_number, :person_name, :operation_date, 
                        :beginning_qty, :ending_qty)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":product_desc", $product_desc);
            $stm->bindValue(":control_number", $this->control_number);
            $stm->bindValue(":person_name", $this->person_name);
            $stm->bindValue(":operation_date", $this->process_date);
            $stm->bindValue(":beginning_qty", $beginning_qty);
            $stm->bindValue(":ending_qty", $ending_qty);
            $stm->execute();
            $stm->closeCursor();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function productController() {
        foreach($this->arr_products as $product) {
            $product_name = $product[0];
            $new_beg = $this->fetchPreviousEnd($product[0]);
            $new_end = 0;
            if ($this->activity === "Receive") {
                $new_end = $new_beg + $product[1];
            } else if ($this->activity === "Withdraw") {
                $new_end = $new_beg - $product[1];
            }
            
            $this->insertProduct($product_name, $new_beg, $new_end);
        }
    }

    

}

class Itinerary_Calendar extends Dbh {
    function addMemo($employee_id, $memo_date, $memo_title, $memo_message) {
        try {
            $query = "INSERT INTO memos (memo_user_id, memo_date, memo_title, memo_message) VALUES 
                (:employee_id, :memo_date, :memo_title, :memo_message)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":employee_id", $employee_id);
            $stm->bindValue(":memo_date", $memo_date);
            $stm->bindValue(":memo_title", $memo_title);
            $stm->bindValue(":memo_message", $memo_message);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e;
        }
    }

    function fetchMemos() {
        try {
            $query = "SELECT m.memo_id, CONCAT(e.employee_fName,' ',e.employee_mName,' ',e.employee_lName) AS employee_name, m.memo_date, m.memo_title, m.memo_message
                        FROM memos m
                        INNER JOIN employees e ON e.employee_id = m.memo_user_id;";
            $stm = $this->connect()->prepare($query);
            $stm->execute();
            $all_memos = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();

            return $all_memos;
        } catch(PDOException $e) {
            echo $e;
        }
    }

    function deleteMemo($memo_id) {
        try {
            $query = "DELETE FROM memos WHERE memo_id = :memo_id";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":memo_id", $memo_id);
            $stm->execute();
            $stm->closeCursor();
        } catch(PDOException $e) {
            echo $e;
        }
    }

    function memoIdExists($memo_id) {
        try {
            $query = "SELECT EXISTS(SELECT * FROM memos WHERE memo_id = :memo_id)";
            $stm = $this->connect()->prepare($query);
            $stm->bindValue(":memo_id", $memo_id);
            $stm->execute();
            $memo_exists = $stm->fetch();
            $stm->closeCursor();

            if ($memo_exists[0] > 0) {
                return true;
            }
            return false;

        } catch(PDOException $e) {
            echo $e;
        }
    }
}