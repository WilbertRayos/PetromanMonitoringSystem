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
    <meta name="viewport" content="width=device-width, 
                                    initial-scale=1, 
                                    shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/main.js"></script>
    <title>Finance Job Order</title>
  </head>
  <body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h3 class="display-4 my-4 page-title">Job Orders</h3>
        <div class="row md-mt-3 justify-content-end">
            <div class="col-4">
                <div class="input-group mb-3">
                    <!-- <input type="text" class="form-control" placeholder="Job Order #" aria-label="Job Order #" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div> -->
                </div>
            </div>
        </div>

        <table class="table table-sm">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Job Order #</th>
            <th scope="col">Company</th>
            <th scope="col">Remaining Balance</th>
            <th scope="col">Aging</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $obj_1 = new Finance_Job_Order;
            $all_jo = $obj_1->fetchAllJobOrderFinance();
            foreach ($all_jo as $jo_arr) {
              $remaining_balance = $jo_arr['total_amount'] - $jo_arr['amount_paid'];
          ?>
            <tr>
                <td>
                  <a href="#" id="job_order_number"><?php echo $jo_arr['job_order_number']; ?></button>
                </td>
                <td><?php echo $jo_arr['client_name']; ?></td>
                  <?php 
                    if ($remaining_balance <= 0) {
                  ?>
                <td><?php echo "0.00 Php"; ?></td>               
                <td><?php echo $jo_arr['last_payment']; ?></td>
                <td><?php echo "Fully Paid"; ?></td>
                <?php
                  }else {
                ?>
                  <td><?php echo number_format($remaining_balance,2)." Php"; ?></td>               
                  <td><?php echo $jo_arr['aging']; ?></td>
                  <td><?php echo "Unpaid" ?></td>
                <?php
                  }
                ?>
            </tr>
          <?php
            }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
  <script>
      $('a#job_order_number').on('click', function() {
        var jo_num = $(this).text();
        var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/'));
        window.location.href = dir+"/finance_job_order_view.php?jo_num="+jo_num;
      })
  </script>
</html>