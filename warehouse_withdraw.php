<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

$obj_products = new Fetch_Warehouse_Products;
$obj_products->fetchProductFromDatabase();
$products = $obj_products->getProducts();


if (isset($_POST['withdraw_save'])) {
    $arr = json_decode($_POST['withdraw_item_array']);
    if (!isset($_POST["withdraw_number"]) || empty($_POST["withdraw_number"])) {
        echo "<script>alert('Please enter RR Control Number');</script>";
    } else if (!isset($_POST["withdraw_clientName"]) || empty($_POST["withdraw_clientName"])) {
        echo "<script>alert('Please enter Withdraw by');</script>";
    } else if (count($arr) < 1) {
        echo "<script>alert('Please enter products');</script>";
    } else {
        $db_obj1 = new Process_Warehouse_Products("Withdraw",$_POST["withdraw_number"], $_POST["withdraw_date"], $_POST["withdraw_clientName"], $arr);
        $db_obj1->productController();
    }


    
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
        <h3 class="display-4">Warehouse Withdraw</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="withdraw_information">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="withdraw_number">WS Control Number </label>
                    <input class="form-control" id="withdraw_number" name="withdraw_number" />
                </div>
                <div class="form-group col-md-6">
                    <label for="withdraw_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="withdraw_date" name="withdraw_date" value="<?php echo date('m/d/Y');?>" readonly/>
                </div>
                <div class="form-group col-md-12">
                    <label for="withdraw_clientName">Withdrawd By </label>
                    <input class="form-control" id="withdraw_clientName" name="withdraw_clientName" value="<?php echo $_SESSION['employee_fName'].' '.$_SESSION['employee_mName'].' '.$_SESSION['employee_lName'];?>" />
                </div>
            </div>
            <hr />
            <input type="hidden" id="withdraw_item_array" name="withdraw_item_array">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="withdraw_save" name="withdraw_save">Save</button>
                </div>
                <div class="form-group col-md-3">
                    <a href="warehouse.php" type="button" class="form-control btn btn-danger" id="withdraw_cancel" name="withdraw_cancel">Cancel</a>
                </div>
            </div>
            
        </form>
        
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="withdraw_items">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="withdraw_description">Product Name</label>
                    <select class="form-control" id="withdraw_description" name="withdraw_description">
                        <?php
                            foreach($products as $product) {
                                echo "<option>{$product}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label for="withdraw_quantity">Quantity</label>
                    <input type="number" class="form-control" id="withdraw_quantity" name="withdraw_quantity" min="1" />
                </div>
                <div class="form-group col-md-3">
                    <label for="withdraw_add">&nbsp</label>
                    <button type="button" class="form-control btn btn-primary" id="withdraw_add" name="withdraw_add">Add</button>
                </div>
            </div>
            <table class="table table-striped table-sm" id="withdraw_item_table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <hr />
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        let arr_withdraw_items = [];
        $(document).ready(function() {
            let ctr1 = 0;
            
            $("#withdraw_add").on('click', function() {
                description = $('#withdraw_description').val();
                quantity = $('#withdraw_quantity').val();
                if (!description) {
                    alert("Item description must not be empty");
                } else if ((!quantity) || quantity <= 0) {
                    alert("Quantity must be greater than 0");
                }else {
                    arr_withdraw_items.push([description, quantity]);

                    for (x of arr_withdraw_items) {
                        alert(x);
                    }

                    new_row = "<tr> \
                                <td>"+description+"</td> \
                                <td>"+quantity+"</td> \
                                <td><button type='button' class='btn btn-outline-danger btn-sm' onClick='deleteRow(this)'>Delete</button></td>";
                                
                    withdraw_items_tbl = $('table tbody');
                    withdraw_items_tbl.append(new_row);
                    // $('#withdraw_totalPayment').val(computeTotal);
                    // $('#withdraw_description').val("");
                    // $('#withdraw_unit').val("");
                    // $('#withdraw_quantity').val("");
                    // $('#withdraw_unitPrice').val("");
                    $('#withdraw_item_array').val(JSON.stringify(arr_withdraw_items));
                }
                // description = $('#withdraw_description').val();
                // unit = $('#withdraw_unit').val();
                // quantity = $('#withdraw_quantity').val();
                // unitPrice = $('#withdraw_unitPrice').val();
                // item_amount = parseFloat($('#withdraw_quantity').val()*$('#withdraw_unitPrice').val());
            });
        });

        function deleteRow(cell){
            var row = $(cell).parents('tr');
            var rIndex = row[0].rowIndex;

            arr_withdraw_items.splice(rIndex-1, 1);

            document.getElementById('withdraw_item_table').deleteRow(rIndex);
        }

        // function computeTotal(){
            
        //     var totalAmount = 0.0 + parseFloat($('#withdraw_mobilization').val());
        //     var tbl = document.getElementById('withdraw_item_table');
            
        //     for(var row=1, n=tbl.rows.length; row<n; row++){
        //         totalAmount += parseFloat(tbl.rows[row].cells[4].innerHTML);
        //     }

        //     return totalAmount;

        // }

    </script>
  </body>
</html>