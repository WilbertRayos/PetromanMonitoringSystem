<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

$trading_sales_number = $_GET['ts_num'];
require_once("db_ops.php");
$db_obj_tsFinance = new Finance_Trading_Sales();
$ts_finance_info = $db_obj_tsFinance->fetchSpecificTradingSalesFinance($trading_sales_number);

$date_created = $ts_finance_info['date_created'];
$total_amount = $ts_finance_info['total_amount'];
$terms_of_payment = $ts_finance_info['terms_of_payment'];
$remaining_balance = $ts_finance_info['remaining_balance'];


if (isset($_POST['save_payment'])) {
    if (!isset($_POST['amount_paid']) || empty($_POST['amount_paid'])) {
        echo "Please enter an amount!";
    } else if (!isset($_POST['bank']) || empty($_POST['bank'])) {
        echo "Please enter bank name";
    } else if (!isset($_POST['reference_number']) || empty($_POST['reference_number'])) {
        echo "Enter reference number";
    } else if (!isset($_POST['deposit_date']) || empty($_POST['deposit_date'])) {
        echo "Please enter deposit date";
    } else {
        $db_obj_tsFinance->insertPayment($trading_sales_number, $_POST['amount_paid'], $_POST['bank'], $_POST['reference_number'],$_POST['deposit_date']);
        // header('Refresh:0');
    }
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
    <link rel="stylesheet" href="css/main.css">
    <title>Trading Sales Finance</title>
  </head>
  <body>
    <?php require('navbar.php');?>
    <div class="container">
        <h3 class="display-4 my-5 page-title"><?php echo $trading_sales_number; ?> Finance </h3>
        <hr />
            <div class="form-row">
                <div class="col-md-6">
                    <form action="<?php echo $path_parts['basename'];?>" method="POST" id="payment">
                        <div class="form-group col-md-12">
                            <label for="date_created">Date Created</label>
                            <input class="form-control" id="date_created" name="date_created" value="<?php echo $date_created?>" readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="total_amount">Total Amount</label>
                            <input class="form-control" id="total_amount" name="total_amount" value="<?php echo round($total_amount,2)?>" readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="terms_of_payment">Terms of Payment</label>
                            <input class="form-control" id="terms_of_payment" name="terms_of_payment" value="<?php echo $terms_of_payment?>" readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="remaining_balance">Remaining Balance</label>
                            <input class="form-control" id="remaining_balance" name="remaining_balance" value="<?php echo round($remaining_balance,2)?>" readonly />
                        </div>
                    </form>
                </div>
                <div class="col-md-6 px-2 py-3 menu-box" >
                    <form action="<?php echo $path_parts['basename'];?>" method="POST" id="payment_information">
                        <div class="form-group col-md-12">
                            <label for="amount_paid">Amount Paid</label>
                            <input class="form-control" id="amount_paid" name="amount_paid" />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="bank">Bank</label>
                            <input class="form-control" id="bank" name="bank" />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="reference_number">Reference #</label>
                            <input class="form-control" id="reference_number" name="reference_number" />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="deposit_date">Deposit Date</label>
                            <input type="date" class="form-control" id="deposit_date" name="deposit_date" />
                        </div>
                        <button type="submit" class="form-control btn btn-primary" id="save_payment" name="save_payment" form="payment_information">Save Payment</button>
                    </form>
                </div>
            </div>
            <hr />
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Deposit Date</th>
                        <th scope="col">Bank</th>
                        <th scope="col">Reference Number</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $all_transactions = $db_obj_tsFinance->fetchSpecificTradingSalesTransaction($trading_sales_number);
                        foreach($all_transactions as $transaction) {
                    ?>
                        <tr>
                            <td><?php echo $transaction["deposit_date"]; ?></td>
                            <td><?php echo $transaction["bank"]; ?></td>
                            <td><?php echo $transaction["reference_number"]; ?></td>
                            <td><?php echo number_format($transaction["amount"], 2,'.',''); ?></td>
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
  </body>
</html>