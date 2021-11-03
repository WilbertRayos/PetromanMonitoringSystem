<?php 
    session_start();
    if(!isset($_SESSION['loggedIn'])){
        header('Location: index.php');
    }
    require_once('db_ops.php');
    $db_obj = new Fetch_All_Users;
    $employees = $db_obj->fetchAllUsers();
    

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <?php include("navbar.php"); ?>
    <div class="container">
        <h2 class="display-4">Admins</h2>
        <hr />
        <p class="lead">Table of admin accounts</p>

        <table class="table table-striped table-sm">
        <thead class="thead-dark">
            <tr>
            <th scope="col">#</th>
            <th scope="col">First Name</th>
            <th scope="col">Middle Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($employees as $employee){
                    if(strcmp($employee['role_desc'], 'Admin') == 0){
                        echo "<tr>
                        <th scope='row'>{$employee['employee_id']}</th>
                        <td>{$employee['employee_fName']}</td>
                        <td>{$employee['employee_mName']}</td>
                        <td>{$employee['employee_lName']}</td>
                        <td>{$employee['employee_email']}</td>
                        </tr>";
                    }
                }
            ?>
        </tbody>
        </table>

        <h2 class="display-4">Agents</h2>
        <hr />
        <p class="lead">Table of agent accounts</p>

        <table class="table table-striped table-sm">
        <thead class="thead-dark">
            <tr>
            <th scope="col">#</th>
            <th scope="col">First Name</th>
            <th scope="col">Middle Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Email</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($employees as $employee){
                    if(strcmp($employee['role_desc'], 'Agent') == 0){
                        echo "<tr>
                        <th scope='row'>{$employee['employee_id']}</th>
                        <td>{$employee['employee_fName']}</td>
                        <td>{$employee['employee_mName']}</td>
                        <td>{$employee['employee_lName']}</td>
                        <td>{$employee['employee_email']}</td>
                        <td><form action='POST' id='aw'><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#employeeInfoModal' name='wews' value='{$employee['employee_id']}'>
                            Edit
                        </button><form></td>
                        </tr>";
                    }
                }

            ?>
        </tbody>
        </table>

        <!-- Button trigger modal -->
        

        <!-- Modal -->
        <div class="modal fade" id="employeeInfoModal" tabindex="-1" role="dialog" aria-labelledby="employeeInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeInfoModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>
    </div>
                <?php if(isset($_POST['wews'])){echo 'wews';}?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>