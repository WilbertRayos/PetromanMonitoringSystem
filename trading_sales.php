<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

require_once('db_ops.php');
    

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Trading Sales</title>
    <script src="js/main.js"></script>
  </head>
  <body>
    <?php require 'navbar.php';?>
    <div class="container">
      <h3 class="display-4">Trading Sales</h3>
      <div class="row mt-md-3">
        <!-- Search Bar Column -->
        <div class="col-md-4 order-md-12">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Trading Form #" aria-label="Trading Form #" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
          </div>
        </div>
        <!-- Create Trading Sales Column -->
        <div class="col-md-8 order-md-1" >
          <a href="trading_sales_form.php" class="btn btn-primary">Create Trading Sales</a>
        </div>
      </div>
 
      <table class="table table-sm">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Order Form #</th>
            <th scope="col">Company Name</th>
            <th scope="col">Trade Value</th>
            <th scope="col">Agent</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $obj_1 = new Fetch_All_Job_Orders;
            $all_jo = $obj_1->fetchAllJobOrders();
            foreach ($all_jo as $jo_arr) {
          ?>
          <tr>
            <td>
              <a href="#" id="job_order_number"><?php echo $jo_arr[0]; ?></button>
            </td>
            <td><?php echo $jo_arr[1]; ?></td>
            <td><?php echo $jo_arr[2]; ?></td>
            <td><?php echo $jo_arr[3]; ?></td>
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
        window.location.href = "/petroman/job_order_view.php?jo_num="+jo_num;
      })
    </script>
  </body>
</html>