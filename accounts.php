<?php 
    session_start();
    if(!isset($_SESSION['loggedIn'])){
        header('Location: index.php');
    }
    require_once('db_ops.php');
    $db_obj = new Fetch_All_Users;
    $employees = $db_obj->fetchAllUsers();
    


    // Add New Account
    if(isset($_POST['addAccount'])){
        echo($_POST['role']);
    }

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
        <div class="row float-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newAccountModal">
              Create New Account
            </button>
        </div>
        <h2 class="display-4">Admins</h2>
        <hr />
        <div class="row">
            <div class="col-md-10">        
                <p class="lead">Table of admin accounts</p>
            </div>
            <div class="col-md-2">
                
            </div>
        </div>
       
       <!-- New Account -->
       <!-- Modal -->
        <div class="modal fade" id="newAccountModal" tabindex="-1" role="dialog" aria-labelledby="newAccountModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="newAccountModalLabel">New Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="newAccountInfo">
                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="role" id="rdbAdmin" value="admin" checked>
                          <label class="form-check-label" for="rdbAdmin">
                            Admin
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="role" id="rdbAgent" value="agent">
                          <label class="form-check-label" for="rdbAgent">
                            Agent
                          </label>
                        </div>
                    </div>
                        <div class="form-group">
                        <label for="create_employee_fName">First Name</label>
                        <input type="input" class="form-control" id="create_employee_fName" name="create_employee_fName">
                        <label for="create_employee_mName">Middle Name</label>
                        <input type="input" class="form-control" id="create_employee_mName" name="create_employee_mName">
                        <label for="create_employee_lName">Last Name</label>
                        <input type="input" class="form-control" id="create_employee_lName" name="create_employee_lName">
                        <label for="create_employee_email">Email</label>
                        <input type="email" class="form-control" id="create_employee_email" name="create_employee_email">
                        <label for="create_employee_password">Password</label>
                        <input class="form-control" type="password" id="create_employee_password" name="create_employee_password">
                        <input type="checkbox" onclick="myFunction()"> Show Password
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="newAccountInfo" name="addAccount">Add</button>
              </div>
            </div>
          </div>
        </div>

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
                    if(strcmp($employee['role_desc'], 'Admin') == 0){
            ?>
                    <tr>
                        <td><?php echo $employee['employee_id'] ?></th>
                        <td><?php echo $employee['employee_fName'] ?></td>
                        <td><?php echo $employee['employee_mName'] ?></td>
                        <td><?php echo $employee['employee_lName'] ?></td>
                        <td><?php echo $employee['employee_email'] ?></td>
                        <td>
                            <button type="button" class="btn btn-outline-success btn-sm btnAdmin_edit" data-toggle="modal" data-target="#employees_accounts">Edit</button>
                            <button type="button" class="btn btn-outline-danger btn-sm">Delete</button>
                        </td>
                    </tr>
            <?php
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
            ?>
                        <tr>
                            <td><?php echo $employee['employee_id'] ?></th>
                            <td><?php echo $employee['employee_fName'] ?></td>
                            <td><?php echo $employee['employee_mName'] ?></td>
                            <td><?php echo $employee['employee_lName'] ?></td>
                            <td><?php echo $employee['employee_email'] ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-success btn-sm btnAgent_edit" data-toggle="modal" data-target="#employees_accounts">Edit</button>
                                <button type="button" class="btn btn-outline-danger btn-sm">Delete</button>
                            </td>
                        </tr>
            <?php
                    }
                }
            ?>

            
        </tbody>
        </table>

        <!-- Button trigger modal -->
        

        <!-- Modal -->
        <div class="modal fade" id="employees_accounts" tabindex="-1" role="dialog" aria-labelledby="employeeInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeInfoModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="userInfo">
                    <div class="form-group">
                        <label for="employee_id">Employee ID</label>
                        <input type="input" class="form-control" id="edit_employee_id">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="edit_roles" id="edit_admin" value="admin">
                          <label class="form-check-label" for="exampleRadios1">
                            Admin
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="edit_roles" id="edit_agent" value="agent">
                          <label class="form-check-label" for="exampleRadios2">
                            Agent
                          </label>
                        </div>
                    </div> 

                        <label for="edit_employee_fName">First Name</label>
                        <input type="input" class="form-control" id="edit_employee_fName" name="edit_employee_fName">
                        <label for="edit_employee_mName">First Name</label>
                        <input type="input" class="form-control" id="edit_employee_mName" name="edit_employee_mName">
                        <label for="edit_employee_lName">First Name</label>
                        <input type="input" class="form-control" id="edit_employee_lName" name="edit_employee_lName">
                        <label for="edit_employee_email">First Name</label>
                        <input type="email" class="form-control" id="edit_employee_email" name="edit_employee_email">
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>
    </div>
                

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
      
      //$('#edit_employee_fName').val('aaasd');
      $(document).ready(function() {
        $('.btnAdmin_edit').on('click', function() {
            //$('#employees_accounts').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            console.log(data);
            $('#edit_employee_id').val(data[0]);
            $('#edit_admin').prop('checked',true);
            $('#edit_employee_fName').val(data[1]);
            $('#edit_employee_mName').val(data[2]);
            $('#edit_employee_lName').val(data[3]);
            $('#edit_employee_email').val(data[4]);
        });

        $('.btnAgent_edit').on('click', function() {
            //$('#employees_accounts').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            console.log(data);
            $('#edit_employee_id').val(data[0]);
            $('#edit_admin').prop('checked',true);
            $('#edit_employee_fName').val(data[1]);
            $('#edit_employee_mName').val(data[2]);
            $('#edit_employee_lName').val(data[3]);
            $('#edit_employee_email').val(data[4]);
        });

      });

      function myFunction() {
        var x = document.getElementById("create_employee_password");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }

</script>
  </body>
</html>