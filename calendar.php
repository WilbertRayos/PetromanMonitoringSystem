<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}
$today = date("F j, Y, l");   
echo $today;

$obj_products = new Fetch_Warehouse_Products;
$obj_products->fetchProductFromDatabase();
$products = $obj_products->getProducts();


if (isset($_POST['memo_save'])) {
    print_r($_POST);
    if (!isset($_POST['date_picker']) || empty($_POST['date_picker'])){
        echo "<script>alert('Please pick a date');</script>";
    } else if (!isset($_POST['memo_title']) || empty($_POST['memo_title'])) {
        echo "<script>alert('Please fill-up title');</script>";
    } else if (!isset($_POST['memo_message']) || empty($_POST['memo_message'])) {
        echo "<script>alert('Please fill-up message');</script>";
    } else {
        echo "nioce";
    }
    // $arr = json_decode($_POST['withdraw_item_array']);
    // if (!isset($_POST["withdraw_number"]) || empty($_POST["withdraw_number"])) {
    //     echo "<script>alert('Please enter RR Control Number');</script>";
    // } else if (!isset($_POST["withdraw_clientName"]) || empty($_POST["withdraw_clientName"])) {
    //     echo "<script>alert('Please enter Withdraw by');</script>";
    // } else if (count($arr) < 1) {
    //     echo "<script>alert('Please enter products');</script>";
    // } else {
    //     $db_obj1 = new Process_Warehouse_Products("Withdraw",$_POST["withdraw_number"], $_POST["withdraw_date"], $_POST["withdraw_clientName"], $arr);
    //     $db_obj1->productController();
    // }


    
    // $db_obj1 = new Add_New_Job_Order;
    // $db_obj1->setJobOrderNumber($_POST['withdraw_number']);
    // $db_obj1->setClientName($_POST['withdraw_clientName']);
    // $db_obj1->setDate($_POST['withdraw_date']);
    // $db_obj1->setRepresentative($_POST['withdraw_representative']);
    // $db_obj1->setContactNumber($_POST['withdraw_contact']);
    // $db_obj1->setTinNumber($_POST['withdraw_tin']);
    // $db_obj1->setAddress($_POST['withdraw_address']);
    // $db_obj1->setProjectLocation($_POST['withdraw_location']);
    // $db_obj1->setTermsOfPayment($_POST['withdraw_cod']);
    // $db_obj1->setMobilization($_POST['withdraw_mobilization']);
    // $db_obj1->setEmployeeID($_SESSION['employee_id']);
    // $arr = json_decode($_POST['withdraw_item_array']);
    // try{
    //     $db_obj1->addNewJobOrder();
    //     foreach($arr as $items[]) {
    //         foreach($items as $item) {
    //             $db_obj1->addJobOrderItems($item[0],$item[1],$item[2],$item[3]);
    //         }
    //     }
    // } catch(Exception $e) {
        
    // }
    
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
   
    <?php require('navbar.php');?>

    <div class="container">
        <h3 class="display-4">Itenerary Calendar</h3>
        <div class="row">
            <div class="col-md-4" style="background-color:green;">
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="date_setter">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="date_picker">Pick Date</label>
                            <input class="form-control" type="date" id="date_picker" name="date_picker">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="memo_title">Title</label>
                            <input class="form-control" type="text" id="memo_title" name="memo_title">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="memo_message">Title</label>
                            <textarea class="form-control" id="memo_message" name="memo_message" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <button type="submit" class="form-control btn btn-primary" id="memo_save" name="memo_save">Post</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8" style="background-color:rebeccapurple;">
                2 of 2
            </div>
        </div>
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  </body>
</html>