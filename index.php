<?php
    session_start();
    require_once('db_ops.php');

    if(isset($_POST['login'])){
        if(!isset($_POST['employee_email']) || empty($_POST['employee_email'])){
            echo "Please enter email";
        }else if(!isset($_POST['employee_password']) || empty($_POST['employee_password'])){
            echo "PlfetchAllUsersease enter password";
        }else{
            $email =$_POST['employee_email'];
            $password = $_POST['employee_password'];

            $db_obj = new User_Login($email);

            if($db_obj->validateUserEmail() == 0){
                echo "No employee exists";
            }else {
                if(!password_verify($_POST['employee_password'], $db_obj->validate_user_password())){
                    echo "incorrect password";
                }else{
                    $db_obj->fetch_user_info();
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['employee_id'] = $db_obj->getEmployeeID();
                    $_SESSION['employee_fName'] = $db_obj->getEmployeeFName();
                    $_SESSION['employee_mName'] = $db_obj->getEmployeeMName();
                    $_SESSION['employee_lName'] = $db_obj->getEmployeeLName();
                    $_SESSION['employee_email'] = $_POST['employee_email'];
                    $_SESSION['employee_password'] = $_POST['employee_password'];
                    $_SESSION['employee_role'] = $db_obj->getEmployeeRole();
                    
                    header('Location: projects.php');
                }

            } 
        }
    }else if(isset($_POST['changePassword'])) {
        if(!isset($_POST['cp_employee_email']) || empty($_POST['cp_employee_email'])) {
            echo "To change password, please enter your email";
        }else if(!isset($_POST['cp_employee_password']) || empty($_POST['cp_employee_password'])){
            echo "Please enter your new password";
        }else if(!isset($_POST['re_cp_employee_password']) || empty($_POST['re_cp_employee_password'])){
            echo "Please re-enter your password";
        }else if(strcmp($_POST['cp_employee_password'],$_POST['re_cp_employee_password']) != 0){
            echo "Your passwords are not similar";
        }else{
            $db_obj = new Change_Password;
            $db_obj->setEmployeeEmail($_POST['cp_employee_email']);
            $db_obj->setEmployeeNewPassword($_POST['cp_employee_password']);
            if($db_obj->validateUserEmail() == 0){
                echo "No such employee exists";
            }else{
                if(!$db_obj->changeUserPassword() == 1){
                    echo "Employee email failed to change";
                }else {
                    echo "Employee email successfully changed";
                }
            }
        }
    }   
?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Login</title>
    <style>
        html,body {
            height: 100%;
        }
    </style>
  </head>
  <body>
    <div class="container h-75 d-flex">
        <div class="jumbotron my-auto mx-auto w-50">
            <h2 class="display-4 text-center">Petroman</h1>
            <hr />
            <form action="index.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="employeeEmail">Email address</label>
                    <input type="email" class="form-control" id="employeeEmail" name="employee_email" aria-describedby="emailHelp" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="employeePassword">Password</label>
                    <input type="password" class="form-control" id="employeePassword" name="employee_password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary" name="login" value="login" fomr="loginForm">Login</button> 
            </form>
            <a type="button" data-toggle="modal" data-target="#exampleModalCenter" class="float-right" href="#">Forgot Password</a>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="POST" id="changePasswordForm">
                        <div class="form-group">
                            <label for="cp_employeeEmail">Email address</label>
                            <input type="email" class="form-control" id="cp_employeeEmail" name="cp_employee_email" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="cp_employeePassword">New Password</label>
                            <input type="password" class="form-control" id="cp_employeePassword" name="cp_employee_password" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <label for="re_cp_employeePassword">Re-enter New Password</label>
                            <input type="password" class="form-control" id="re_cp_employeePassword" name="re_cp_employee_password" placeholder="Re-enter New Password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="changePassword" value="changePassword" form="changePasswordForm">Change Password</button> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  </body>
</html>