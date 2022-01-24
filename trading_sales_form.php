<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

$obj_maintenance = new Maintenance;

if (isset($_POST['ts_save'])) {
    if(!isset($_POST['ts_number']) || empty($_POST['ts_number'])) {
        echo "<script>alert('Please Fill-up Trading Sales Number');</script>";
    } else if(!isset($_POST['ts_clientName']) || empty($_POST['ts_clientName'])) {
        echo "<script>alert('Please Fill-up Client');</script>";
    } else if(!isset($_POST['ts_date']) || empty($_POST['ts_date'])) {
        echo "<script>alert('Please Fill-up Date');</script>";
    } else if(!isset($_POST['ts_representative']) || empty($_POST['ts_representative'])) {
        echo "<script>alert('Please Fill-up Representative');</script>";
    } else if(!isset($_POST['ts_contact']) || empty($_POST['ts_contact']) || is_numeric($_POST['ts_contact']) != 1) {
        echo "<script>alert('Please Fill-up Contact Number Properly');</script>";
    } else if(!isset($_POST['ts_tin']) || empty($_POST['ts_tin'])) {
        echo "<script>alert('Please Fill-up Tin');</script>";
    } else if(!isset($_POST['ts_address']) || empty($_POST['ts_address'])) {
        echo "<script>alert('Please Fill-up Address');</script>";
    } else if(!isset($_POST['ts_item_array']) || empty($_POST['ts_item_array'])) {
        echo "<script>alert('Please Input at least 1 item');</script>";
    } else {
        $db_obj1 = new Create_New_Trading_Sales;
        $db_obj1->setTradingSalesNumber($_POST['ts_number']);
        $db_obj1->setClientName($_POST['ts_clientName']);
        $db_obj1->setDate($_POST['ts_date']);
        $db_obj1->setRepresentative($_POST['ts_representative']);
        $db_obj1->setContactNumber($_POST['ts_contact']);
        $db_obj1->setTinNumber($_POST['ts_tin']);
        $db_obj1->setAddress($_POST['ts_address']);
        $db_obj1->setTermsOfPayment($_POST['ts_cod']);
        $db_obj1->setEmployeeID($_SESSION['employee_id']);
        $arr = json_decode($_POST['ts_item_array']);
        try{
            $db_obj1->addTradingSales();
            $all_products_available = true;
            //Check if stock is enought for all items
            foreach($arr as $items[]) {
                foreach($items as $item) {
                    // $db_obj1->addTradingSalesItems($item[0],$item[1],$item[2],$item[3]);
                    $product_stock = $db_obj1->checkWarehouseStock($item[0]);
                    if (($product_stock - $item[2]) < 0) {
                        $all_products_available = false;
                        echo "<script>alert('Not enough stock');</script>";
                        break;
                    }
                }
            }

            if ($all_products_available) {
                foreach($arr as $items) {
                    $db_obj1->addTradingSalesItems($items[0],$items[1],$items[2],$items[3]);
                }
            }
        } catch(Exception $e) {
            echo "<script>alert('Unexpected Error Occured');</script>";
        }
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
    <script src="js/main.js"></script>
    <title>Trading Sales Form</title>
  </head>
  <body>
   
    <?php require('navbar.php');?>

    <div class="container">
        <h3 class="display-4 my-4 page-title">Trading Sales</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="ts_information">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="ts_number">Order Form Number </label>
                    <input class="form-control" id="ts_number" name="ts_number" />
                </div>
                <div class="form-group col-md-8">
                    <label for="ts_clientName">Client Name </label>
                    <!-- <input class="form-control" id="ts_clientName" name="ts_clientName" /> -->
                    <select class="form-control" id="ts_clientName" name="ts_clientName" >
                        <?php
                            $all_company = $obj_maintenance->fetchAllCompany();
                            foreach ($all_company as $company) {
                        ?>
                            <option><?php echo $company['company_desc']; ?></option>
                        <?php
                            }
                        ?>

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="ts_date" name="ts_date" value="<?php echo date('m/d/Y');?>" readonly/>
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_representative">Representative</label>
                    <input class="form-control" id="ts_representative" name="ts_representative" />
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_contact">Contact Number</label>
                    <input class="form-control" id="ts_contact" name="ts_contact" />
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_tin">TIN#</label>
                    <input class="form-control" id="ts_tin" name="ts_tin" />
                </div>
                <div class="form-group col-md-12">
                    <label for="ts_address">Address</label>
                    <input class="form-control" id="ts_address" name="ts_address" />
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="ts_cod">Terms of Payment</label>
                    <select class="form-control" id="ts_cod" name="ts_cod">
                    <option>COD</option>
                    <option>30 Days</option>
                    <option>60 Days</option>
                    <option>90 Days</option>
                    <option>150 Days</option>
                    <option>180 Days</option>
                    </select>
                </div> 
            </div>
            <hr />
            
            <div class="form-row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="ts_creator">Created By:</label>
                    <input class="form-control" id="ts_creator" name="ts_creator" value="<?php echo $_SESSION['employee_fName']." ".$_SESSION['employee_mName']." ".$_SESSION['employee_lName']?>" readonly />
                </div>
                
                <div class="form-group col-sm-12 col-md-6">
                    <label for="ts_totalPayment">Total Payment</label>
                    <input type="number" class="form-control" id="ts_totalPayment" readonly/>
                </div>
            </div>
            <input type="hidden" id="ts_item_array" name="ts_item_array">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="ts_save" name="ts_save">Save</button>
                </div>
                <div class="form-group col-md-3">
                    <a href="trading_sales.php" type="button" class="form-control btn btn-danger" id="ts_cancel" name="ts_cancel">Cancel</a>
                </div>
            </div>
            
        </form>
        
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="ts_items">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="ts_description">Description</label>
                    <!-- <select class="form-control" id="ts_description" name="ts_description" >
                        <option>CS WHITE</option>
                        <option>CS YELLOW</option>
                        <option>GLASS BEADS</option>
                        <option>LEGACY WHITE</option>
                        <option>LEGACY YELLOW</option>
                        <option>PRIMER</option>
                    </select> -->
                    <select class="form-control" id="ts_description" name="ts_description">
                        <?php
                            $all_products = $obj_maintenance->fetchAllItems();
                            foreach ($all_products as $product) {
                        ?>
                            <option><?php echo $product['product_desc']; ?></option>
                        <?php
                            }
                        ?>

                    </select>
                </div>
                <div class="form-group col-md-2 col-sm-6">
                    <label for="ts_unit">Unit</label>
                    <!-- <select class="form-control" id="ts_unit" name="ts_unit">
                        <option>SQM</option>
                        <option>PC</option>
                        <option>BAGS</option>
                        <option>KG</option>
                        <option>BOX</option>
                    </select> -->
                    <select class="form-control" id="ts_unit" name="ts_unit">
                        <?php
                            $all_units = $obj_maintenance->fetchAllUnits();
                            foreach ($all_units as $unit) {
                        ?>
                            <option><?php echo $unit['unit_desc']; ?></option>
                        <?php
                            }
                        ?>

                    </select>
                </div>
                <div class="form-group col-md-2 col-sm-6">
                    <label for="ts_quantity">Qty.</label>
                    <input type="number" class="form-control" id="ts_quantity" name="ts_quantity"/>
                </div>
                <div class="form-group col-md-2">
                    <label for="ts_unitPrice">Unit Price</label>
                    <input type="number" class="form-control" id="ts_unitPrice" name="ts_unitPrice" />
                </div>
                <div class="form-group col-md-1">
                    <label for="ts_add">&nbsp</label>
                    <button type="button" class="form-control btn btn-primary" id="ts_add" name="ts_add">Add</button>
                </div>
            </div>
            <table class="table table-striped table-sm" id="ts_item_table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">Description</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Amount</th>
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
        let arr_ts_items = [];
        $(document).ready(function() {
            let ctr1 = 0;
            
            $("#ts_add").on('click', function() {
                description = $('#ts_description').val();
                unit = $('#ts_unit').val();
                quantity = $('#ts_quantity').val();
                unitPrice = $('#ts_unitPrice').val();
                item_amount = parseFloat($('#ts_quantity').val()*$('#ts_unitPrice').val());
                
                arr_ts_items.push([description, unit, quantity, unitPrice, item_amount]);

                new_row = "<tr> \
                            <td>"+description+"</td> \
                            <td>"+unit+"</td> \
                            <td>"+quantity+"</td> \
                            <td>"+unitPrice+"</td> \
                            <td>"+item_amount+"</td> \
                            <td><button type='button' class='btn btn-outline-danger btn-sm' onClick='deleteRow(this)'>Delete</button></td>";
                            
                ts_items_tbl = $('table tbody');
                ts_items_tbl.append(new_row);
                $('#ts_totalPayment').val(computeTotal);
                $('#ts_description').val("");
                $('#ts_unit').val("");
                $('#ts_quantity').val("");
                $('#ts_unitPrice').val("");
                $('#ts_item_array').val(JSON.stringify(arr_ts_items));
            });
        });

        function deleteRow(cell){
            var row = $(cell).parents('tr');
            var rIndex = row[0].rowIndex;

            arr_ts_items.splice(rIndex-1, 1);

            document.getElementById('ts_item_table').deleteRow(rIndex);
        }

        function computeTotal(){
            var totalAmount = 0.0;
            var tbl = document.getElementById('ts_item_table');
            
            for(var row=1, n=tbl.rows.length; row<n; row++){
                totalAmount += parseFloat(tbl.rows[row].cells[4].innerHTML);
            }

            return totalAmount;

        }

    </script>
  </body>
</html>