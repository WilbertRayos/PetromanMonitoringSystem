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
    <title>Finance Trading Sales</title>
  </head>
  <body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h3 class="display-4 my-4 page-title">Trading Sales</h3>
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
            <th scope="col">Order Form #</th>
            <th scope="col">Remaining Balance</th>
            <th scope="col">Aging</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $obj_1 = new Finance_Trading_Sales;
            $all_ts = $obj_1->fetchAllTradingSalesFinance();
            foreach ($all_ts as $ts_arr) {
          ?>
            <tr>
                <td>
                <a href="#" id="trading_sales_number"><?php echo $ts_arr['trading_sales_number']; ?></button>
                </td>
                <?php 
                  if ($ts_arr['remaining_balance'] == 0) {
                ?>
                <td><?php echo "0 Php"; ?></td>               
                <td><?php echo $ts_arr['aging']; ?></td>
                <td><?php echo "Fully Paid" ?></td>
                <?php
                  }else {
                ?>
                  <td><?php echo round($ts_arr['remaining_balance'],2)." Php"; ?></td>               
                  <td><?php echo $ts_arr['aging']; ?></td>
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
      $('a#trading_sales_number').on('click', function() {
        var ts_num = $(this).text();
        window.location.href = "/petroman/finance_trading_sales_view.php?ts_num="+ts_num;
      })
  </script>
</html>