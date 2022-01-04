<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

$obj_products = new Fetch_Warehouse_Products;
$obj_products->fetchProductFromDatabase();
$products = $obj_products->getProducts();


if (isset($_POST['receive_save'])) {
    $arr = json_decode($_POST['receive_item_array']);
    if (!isset($_POST["receive_number"]) || empty($_POST["receive_number"])) {
        echo "<script>alert('Please enter RR Control Number');</script>";
    } else if (!isset($_POST["receive_clientName"]) || empty($_POST["receive_clientName"])) {
        echo "<script>alert('Please enter Received by');</script>";
    } else if (count($arr) < 1) {
        echo "<script>alert('Please enter products');</script>";
    } else {
        $db_obj1 = new Process_Warehouse_Products("Receive",$_POST["receive_number"], $_POST["receive_date"], $_POST["receive_clientName"], $arr);
        $db_obj1->productController();
    }


    
    // $db_obj1 = new Add_New_Job_Order;
    // $db_obj1->setJobOrderNumber($_POST['receive_number']);
    // $db_obj1->setClientName($_POST['receive_clientName']);
    // $db_obj1->setDate($_POST['receive_date']);
    // $db_obj1->setRepresentative($_POST['receive_representative']);
    // $db_obj1->setContactNumber($_POST['receive_contact']);
    // $db_obj1->setTinNumber($_POST['receive_tin']);
    // $db_obj1->setAddress($_POST['receive_address']);
    // $db_obj1->setProjectLocation($_POST['receive_location']);
    // $db_obj1->setTermsOfPayment($_POST['receive_cod']);
    // $db_obj1->setMobilization($_POST['receive_mobilization']);
    // $db_obj1->setEmployeeID($_SESSION['employee_id']);
    // $arr = json_decode($_POST['receive_item_array']);
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
        <h3 class="display-4">Warehouse Receive</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="receive_information">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="receive_number">RR Control Number </label>
                    <input class="form-control" id="receive_number" name="receive_number" />
                </div>
                <div class="form-group col-md-6">
                    <label for="receive_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="receive_date" name="receive_date" value="<?php echo date('m/d/Y');?>" readonly/>
                </div>
                <div class="form-group col-md-12">
                    <label for="receive_clientName">Received By </label>
                    <input class="form-control" id="receive_clientName" name="receive_clientName" />
                </div>
            </div>
            <hr />
            <input type="hidden" id="receive_item_array" name="receive_item_array">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="receive_save" name="receive_save">Save</button>
                </div>
                <div class="form-group col-md-3">
                    <a href="projects.php" type="button" class="form-control btn btn-danger" id="receive_cancel" name="receive_cancel">Cancel</a>
                </div>
            </div>
            
        </form>
        
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="receive_items">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="receive_description">Product Name</label>
                    <select class="form-control" id="receive_description" name="receive_description">
                        <?php
                            foreach($products as $product) {
                                echo "<option>{$product}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label for="receive_quantity">Quantity</label>
                    <input type="number" class="form-control" id="receive_quantity" name="receive_quantity" min="1" />
                </div>
                <div class="form-group col-md-3">
                    <label for="receive_add">&nbsp</label>
                    <button type="button" class="form-control btn btn-primary" id="receive_add" name="receive_add">Add</button>
                </div>
            </div>
            <table class="table table-striped table-sm" id="receive_item_table">
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
        let arr_receive_items = [];
        $(document).ready(function() {
            let ctr1 = 0;
            
            $("#receive_add").on('click', function() {
                description = $('#receive_description').val();
                quantity = $('#receive_quantity').val();
                if (!description) {
                    alert("Item description must not be empty");
                } else if ((!quantity) || quantity <= 0) {
                    alert("Quantity must be greater than 0");
                }else {
                    arr_receive_items.push([description, quantity]);

                    for (x of arr_receive_items) {
                        alert(x);
                    }

                    new_row = "<tr> \
                                <td>"+description+"</td> \
                                <td>"+quantity+"</td> \
                                <td><button type='button' class='btn btn-outline-danger btn-sm' onClick='deleteRow(this)'>Delete</button></td>";
                                
                    receive_items_tbl = $('table tbody');
                    receive_items_tbl.append(new_row);
                    // $('#receive_totalPayment').val(computeTotal);
                    // $('#receive_description').val("");
                    // $('#receive_unit').val("");
                    // $('#receive_quantity').val("");
                    // $('#receive_unitPrice').val("");
                    $('#receive_item_array').val(JSON.stringify(arr_receive_items));
                }
                // description = $('#receive_description').val();
                // unit = $('#receive_unit').val();
                // quantity = $('#receive_quantity').val();
                // unitPrice = $('#receive_unitPrice').val();
                // item_amount = parseFloat($('#receive_quantity').val()*$('#receive_unitPrice').val());
            });
        });

        function deleteRow(cell){
            var row = $(cell).parents('tr');
            var rIndex = row[0].rowIndex;

            arr_receive_items.splice(rIndex-1, 1);

            document.getElementById('receive_item_table').deleteRow(rIndex);
        }

        // function computeTotal(){
            
        //     var totalAmount = 0.0 + parseFloat($('#receive_mobilization').val());
        //     var tbl = document.getElementById('receive_item_table');
            
        //     for(var row=1, n=tbl.rows.length; row<n; row++){
        //         totalAmount += parseFloat(tbl.rows[row].cells[4].innerHTML);
        //     }

        //     return totalAmount;

        // }

    </script>
  </body>
</html>