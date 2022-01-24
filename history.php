<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}


require_once('db_ops.php');
$obj_maintenance = new Maintenance;
$all_employees = $obj_maintenance->fetchAllUsers();

$obj_history = new History;
$employee_history = $obj_history->fetchHistory("");

if(isset($_POST['btn_filter'])) {
    $employee_history = $obj_history->fetchHistory($_POST['employee_email']);
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

    <title>History</title>
    <script src="js/main.js"></script>
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <?php require 'navbar.php';?>
    <div class="container">
      <h3 class="display-4 my-4 page-title">History</h3>
      <form action="<?php echo $path_parts['basename'];?>" method="POST" id="history_search">
            
        <div class="form-group row">
            <label for="employee_email" class="col-md-2 col-form-label">Email</label>
            <div class="col-md-8">
                <select class="form-control" id="employee_email" name="employee_email">
                    <option></option>
                    <?php
                        foreach($all_employees as $employee) {    
                    ?>
                    <option><?php echo $employee['employee_email']; ?></option>
                    <?php 
                        }
                    ?>
                    
                </select>
            </div>
            <button type="submit" class="btn btn-success" id="btn_filter" name="btn_filter" form="history_search">Filter</button>
        </div>
      </form>
        
      <table class="table table-striped table-sortable" id="printTable">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($employee_history as $employee) {
                        $dt = new DateTime($employee['history_date']);
                        $date = $dt->format('n/j/Y');
                        $time = $dt->format('H:i');
                ?>
                    <tr>
                        <td><?php echo $date; ?></td>
                        <td><?php echo $time; ?></td>
                        <td><?php echo $employee['history_action']; ?></td>
                        <td><?php echo $employee['history_remarks']; ?></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>


    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
  
    <script>
      $('a#job_order_number').on('click', function() {
        var jo_num = $(this).text();
        var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/'));
        window.location.href = dir+"/job_order_view.php?jo_num="+jo_num;
      })
    </script>
  </body>
</html>