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

    <title>Warehouse</title>
    <script src="js/main.js"></script>
  </head>
  <body>
    <?php require 'navbar.php';?>
    <div class="container">
      <h4 class="display-4">Warehouse</h4>
      <div class="row mt-md-3">
        <!-- Search Bar Column -->
        <div class="col-md-4 order-md-12">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Job Order #" aria-label="Job Order #" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
          </div>
        </div>
        <!-- Create Job Order Column -->
        <div class="col-md-1 order-md-1" >
          <a href="warehouse_receive.php" class="btn btn-success">Receive</a>
        </div>
        <div class="col-md-7 order-md-1" >
          <a href="warehouse_withdraw.php" class="btn btn-warning">Withdraw</a>
        </div>
      </div>
 

      

      <h4 class="display-5">Summary</h4>
      <table class="table table-sm">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Product Name</th>
            <th scope="col">Activity</th>
            <th scope="col">Control Number</th>
            <th scope="col">Person Name</th>
            <th scope="col">Operation Date</th>
            <th scope="col">Beginning Quantity</th>
            <th scope="col">Ending Quantity</th>
          </tr>
        </thread>
        <tbody>
          <?php
            $obj_fetch_warehouse_summary = new Fetch_Warehouse_Summary;
            $products = $obj_fetch_warehouse_summary->fetch_product_summary();
            foreach ($products as $product) {
              
          ?>
            <tr>
              <td><?php echo $product['product_desc']; ?></td>
              <td><?php echo $product['activity']; ?></td>
              <td><?php echo $product['control_number']; ?></td>
              <td><?php echo $product['person_name']; ?></td>
              <td><?php echo $product['operation_date']; ?></td>
              <td><?php echo $product['beginning_qty']; ?></td>
              <td><?php echo $product['ending_qty']; ?></td>
            </tr>
          <?php
            }

          ?>
        </tbody>
      </table>
      <hr/>
      <h4 class="display-5">All Transactions</h4>
      
          <?php
            $obj_fetch_warehouse_summary = new Fetch_Warehouse_Summary;
            $transactions = $obj_fetch_warehouse_summary->fetch_all_transactions();
            $current_date = "";
            foreach ($transactions as $transaction) {
              if ($current_date === "") {
                $current_date = $transaction['operation_date'];

            ?>
                <table class="table table-sm">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Control Number</th>
                    <th scope="col">Person Name</th>
                    <th scope="col">Operation Date</th>
                    <th scope="col">Beginning Quantity</th>
                    <th scope="col">Ending Quantity</th>
                  </tr>
                </thread>
                <tbody>
                  <tr>
                    <td><?php echo $transaction['product_desc']; ?></td>
                    <td><?php echo $transaction['activity']; ?></td>
                    <td><?php echo $transaction['control_number']; ?></td>
                    <td><?php echo $transaction['person_name']; ?></td>
                    <td><?php echo $transaction['operation_date']; ?></td>
                    <td><?php echo $transaction['beginning_qty']; ?></td>
                    <td><?php echo $transaction['ending_qty']; ?></td> 
                  </tr>
            <?php
              } else if ($current_date != $transaction['operation_date']) {
                $current_date = $transaction['operation_date'];

           ?>
                  <table class="table table-sm">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Control Number</th>
                    <th scope="col">Person Name</th>
                    <th scope="col">Operation Date</th>
                    <th scope="col">Beginning Quantity</th>
                    <th scope="col">Ending Quantity</th>
                  </tr>
                </thread>
                <tbody>
                  <tr>
                    <td><?php echo $transaction['product_desc']; ?></td>
                    <td><?php echo $transaction['activity']; ?></td>
                    <td><?php echo $transaction['control_number']; ?></td>
                    <td><?php echo $transaction['person_name']; ?></td>
                    <td><?php echo $transaction['operation_date']; ?></td>
                    <td><?php echo $transaction['beginning_qty']; ?></td>
                    <td><?php echo $transaction['ending_qty']; ?></td> 
                  </tr>
            <?php
              } else if ($current_date == $transaction['operation_date']) {
            ?>
                  <tr>
                    <td><?php echo $transaction['product_desc']; ?></td>
                    <td><?php echo $transaction['activity']; ?></td>
                    <td><?php echo $transaction['control_number']; ?></td>
                    <td><?php echo $transaction['person_name']; ?></td>
                    <td><?php echo $transaction['operation_date']; ?></td>
                    <td><?php echo $transaction['beginning_qty']; ?></td>
                    <td><?php echo $transaction['ending_qty']; ?></td>  
                  </tr>  
            <?php
              }
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