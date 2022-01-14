<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

require_once('db_ops.php');

$trading_sales_number = $_GET['ts_num'];
$db_obj_1 = new Fetch_Specific_Trading_Sales($_GET['ts_num']);
$ts_information = $db_obj_1->fetchTradingSalesInformation();
$all_ts_items = $db_obj_1->fetchTradingSalesItems();

if (isset($_POST['ts_update'])){
    $arr = json_decode($_POST['ts_item_array']);
    $db_obj_updateTradingSales = new Update_Trading_Sales($trading_sales_number);
    $db_obj_updateTradingSales->returnStockToWarehouse();
    $db_obj_updateTradingSales->deleteTradingSalesItem($arr);
    $db_obj_updateTradingSales->updateTradingSalesItems($arr);
    $db_obj_updateTradingSales->updateTradingSalesInformation($_POST['ts_number'], $_POST['ts_clientName'], $_POST['ts_representative'], $_POST['ts_contact'],
    $_POST['ts_address'],$_POST['ts_date'],$_POST['ts_tin'],$_POST['ts_cod']);
    $db_obj_updateTradingSales->withdrawStocksFromWarehouse($_POST['ts_number'],$_POST['ts_date'],$_POST['ts_clientName'],$arr);
    
    header('Reload: 0');
}



// Checklist
$ts_count = $db_obj_1->checkExistingChecklist();

// If checklist already exists
if ($ts_count > 0) {
    $ts_checklist = $db_obj_1->fetchExistingChecklist();

    $or_control_number = $ts_checklist['or_cn'];
    $or_date = $ts_checklist['or_date'];
    $ar_control_number = $ts_checklist['ar_cn'];
    $ar_date = $ts_checklist['ar_date'];
    $ws_control_number = $ts_checklist['ws_cn'];
    $ws_date = $ts_checklist['ws_date'];
    $cr_control_number = $ts_checklist['cr_cn'];
    $cr_date = $ts_checklist['cr_date'];
    $dr_control_number = $ts_checklist['dr_cn'];
    $dr_date = $ts_checklist['dr_date'];
    $checklist_2303 = $ts_checklist['checklist_2303_2307'];
    $checklist_soa = $ts_checklist['soa'];
    $checklist_materials = $ts_checklist['total_materials_used']; 
} else {
    $or_control_number = "";
    $or_date = "";
    $ar_control_number = "";
    $ar_date = "";
    $ws_control_number = "";
    $ws_date = "";
    $cr_control_number = "";
    $cr_date = "";
    $dr_control_number = "";
    $dr_date = "";
    $checklist_2303 = "";
    $checklist_soa = "";
    $checklist_materials = ""; 
}


if (isset($_POST['save_checklist'])) {
    if (!isset($_POST['or_control_number']) || empty($_POST['or_control_number']) || !isset($_POST['or_date']) || empty($_POST['or_date'])) {
        echo "Fill-up OR information";
    } else if (!isset($_POST['ar_control_number']) || empty($_POST['ar_control_number']) || !isset($_POST['ar_date']) || empty($_POST['ar_date'])) {
        echo "Fill-up AR information";
    } else if (!isset($_POST['ws_control_number']) || empty($_POST['ws_control_number']) || !isset($_POST['ws_date']) || empty($_POST['ws_date'])) {
        echo "Fill-up WS information";
    } else if (!isset($_POST['cr_control_number']) || empty($_POST['cr_control_number']) || !isset($_POST['cr_date']) || empty($_POST['cr_date'])) {
        echo "Fill-up CR information";
    } else if (!isset($_POST['dr_control_number']) || empty($_POST['dr_control_number']) || !isset($_POST['dr_date']) || empty($_POST['dr_date'])) {
        echo "Fill-up DR information";
    } else if (!isset($_POST['2307']) || empty($_POST['2307'])) {
        echo "Fill-up 2303/2307 information";
    } else if (!isset($_POST['soa']) || empty($_POST['soa'])) {
        echo "Fill-up SOA information";
    } else if (!isset($_POST['total_material_used']) || empty($_POST['total_material_used'])) {
        echo "Fill-up total_material_used information";
    } else {
        if ($ts_count['total_number'] == 0) {
            $db_obj_2 = new Add_New_Trading_Sales_Checklist($_GET['ts_num'], 
                                            $_POST['or_control_number'], $_POST['or_date'], 
                                            $_POST['ar_control_number'], $_POST['ar_date'], 
                                            $_POST['ws_control_number'], $_POST['ws_date'],
                                            $_POST['cr_control_number'], $_POST['cr_date'],
                                            $_POST['dr_control_number'], $_POST['dr_date'],
                                            $_POST['2307'], $_POST['soa'], $_POST['total_material_used']
                                        );
            $new_checklist = $db_obj_2->addNewChecklist();
        } else {
            $db_obj_3 = new Update_Trading_Sales_Checklist(
                $_GET['ts_num'], $_POST['or_control_number'], $_POST['or_date'],
                $_POST['ar_control_number'], $_POST['ar_date'], $_POST['ws_control_number'], $_POST['ws_date'],
                $_POST['cr_control_number'], $_POST['cr_date'], $_POST['dr_control_number'], $_POST['dr_date'],
                $_POST['2307'], $_POST['soa'], $_POST['total_material_used']
            );
            $db_obj_3->updateChecklist();
        }
        header("Location: trading_sales.php");
    }
    
}  
if (isset($_POST['ts_delete'])) {
    $arr_items = json_decode($_POST['ts_item_array']);
    $obj_ts_delete = new Delete_Specific_Trading_Sales($_POST["ts_number"],$_SESSION['employee_fName']." ".$_SESSION['employee_mName']." ".$_SESSION['employee_lName'],  $arr_items);
    $obj_ts_delete->returnItemsToWarehouse();
    $obj_ts_delete->deleteTradingSales();
    header("Location: trading_sales.php");
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
    <title>View Trading Sales</title>
  </head>
  <body>

    <?php require('navbar.php');?>

    <div class="container">
        <h3 class="display-4 my-4 page-title">Trading Sales</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="ts_information">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="ts_number">Order Form Number</label>
                    <input class="form-control" id="ts_number" name="ts_number" value="<?php echo $ts_information['trading_sales_number']; ?>" />
                </div>
                <div class="form-group col-md-8">
                    <label for="ts_clientName">Client Name </label>
                    <input class="form-control" id="ts_clientName" name="ts_clientName" value="<?php echo $ts_information['client_name']; ?>"/>
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="ts_date" name="ts_date" value="<?php echo $ts_information['trading_sales_date'];?>" readonly/>
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_representative">Representative</label>
                    <input class="form-control" id="ts_representative" name="ts_representative" value="<?php echo $ts_information['representative']; ?>"/>
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_contact">Contact Number</label>
                    <input class="form-control" id="ts_contact" name="ts_contact" value="<?php echo $ts_information['contact_number']; ?>" />
                </div>
                <div class="form-group col-md-4">
                    <label for="ts_tin">TIN#</label>
                    <input class="form-control" id="ts_tin" name="ts_tin" value="<?php echo $ts_information['tin_number']; ?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label for="ts_address">Address</label>
                    <input class="form-control" id="ts_address" name="ts_address" value="<?php echo $ts_information['address']; ?>"/>
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="ts_cod">Terms of Payment</label>
                    <select class="form-control" id="ts_cod" name="ts_cod">
                        <label for="jo_cod">Terms of Payment</label>
                        <option value="COD" <?php if($ts_information['terms_of_payment'] == "COD") echo 'selected="selected"';?>>COD</option>
                        <option value="30" <?php if($ts_information['terms_of_payment'] == "30") echo 'selected="selected"';?>>30</option>
                        <option value="60" <?php if($ts_information['terms_of_payment'] == "60") echo 'selected="selected"';?>>60</option>
                        <option value="90" <?php if($ts_information['terms_of_payment'] == "90") echo 'selected="selected"';?>>90</option>
                        <option value="150" <?php if($ts_information['terms_of_payment'] == "150") echo 'selected="selected"';?>>150</option>
                        <option value="180" <?php if($ts_information['terms_of_payment'] == "180") echo 'selected="selected"';?>>180</option>
                    </select>
                </div> 
            </div>
            <hr />
            
            <div class="form-row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="ts_creator">Created By:</label>
                    <input class="form-control" id="ts_creator" name="ts_creator" value="<?php echo $ts_information['employee_name']; ?>" readonly />
                </div>
                
                <div class="form-group col-sm-12 col-md-6">
                    <label for="ts_totalPayment">Total Payment</label>
                    <input type="number" class="form-control" id="ts_totalPayment" value="<?php echo number_format((float)$ts_information['ts_sum'],2,'.',''); ?>" readonly/>
                </div>
            </div>
            <input type="hidden" id="ts_item_array" name="ts_item_array">
            <div class="form-row">
                    <?php 
                        if ($_SESSION['employee_role'] == "Admin") {
                    ?>
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="ts_update" name="ts_update" form="ts_information">Update</button>
                </div>
                    <?php } ?>
                <div class="form-group col-md-3">
                    <button type="button" class="font-control btn btn-info" data-toggle="modal" data-target="#checklistModal">
                        Checklist
                    </button>
                </div>
                <div class="form-group col-md-3">
                    <a href="trading_sales.php" type="button" class="form-control btn btn-danger" id="ts_cancel" name="ts_cancel">Cancel</a>
                </div>
                <div class="form-group col-md-3">
                    <!-- <button type="submit" class="form-control btn btn-outline-danger" id="ts_delete" name="ts_delete" form="ts_information">Delete</button> -->
                </div> 
            </div>
            
        </form>
        
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="ts_items">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="ts_description">Description</label>
                    <select class="form-control" id="ts_description" name="ts_description" >
                        <option>CS WHITE</option>
                        <option>CS YELLOW</option>
                        <option>GLASS BEADS</option>
                        <option>LEGACY WHITE</option>
                        <option>LEGACY YELLOW</option>
                        <option>PRIMER</option>
                    </select>
                </div>
                <div class="form-group col-md-2 col-sm-6">
                    <label for="ts_unit">Unit</label>
                    <input class="form-control" id="ts_unit" name="ts_unit" />
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
                    <?php 
                        foreach ($all_ts_items as $tsb_order_item) {
                             
                    ?>
                        <tr>
                            <td><?php echo $tsb_order_item['description'] ?></td>
                            <td><?php echo $tsb_order_item['unit'] ?></td>
                            <td><?php echo $tsb_order_item['quantity'] ?></td>
                            <td><?php echo $tsb_order_item['unit_price'] ?></td>
                            <td><?php echo $tsb_order_item['quantity']*$tsb_order_item['unit_price'] ?></td>
                            <td><button type='button' class='btn btn-outline-danger btn-sm' onClick='deleteRow(this)'>Delete</button></td>
                        </tr>

                    <?php 
                        }
                    ?>
                </tbody>
            </table>
            <hr />
        </form>
            
            
        <!-- Checklist Modal -->
        <div class="modal fade" id="checklistModal" tabindex="-1" role="dialog" aria-labelledby="checklistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checklistModalLabel">Checklist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="form_checklist">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="or_control_number">OR</label>
                        </div>
                        <div class="col-md-5">
                            <input class="form-control" id="or_control_number" name="or_control_number" placeholder="Control Number" value="<?php echo $or_control_number ?>"/>
                        </div>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="or_date" name="or_date"  value="<?php echo $or_date ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="ar_control_number">AR</label>
                        </div>
                        <div class="col-md-5">
                            <input class="form-control" id="ar_control_number" name="ar_control_number" placeholder="Control Number"  value="<?php echo $ar_control_number ?>"/>
                        </div>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="ar_date" name="ar_date"  value="<?php echo $ar_date ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="ws_control_number">WS</label>
                        </div>
                        <div class="col-md-5">
                            <input class="form-control" id="ws_control_number" name="ws_control_number" placeholder="Control Number"  value="<?php echo $ws_control_number ?>"/>
                        </div>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="ws_date" name="ws_date"  value="<?php echo $ws_date ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="cr_control_number">CR</label>
                        </div>
                        <div class="col-md-5">
                            <input class="form-control" id="cr_control_number" name="cr_control_number" placeholder="Control Number"  value="<?php echo $cr_control_number ?>"/>
                        </div>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="cr_date" name="cr_date"  value="<?php echo $cr_date ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="dr_control_number">DR</label>
                        </div>
                        <div class="col-md-5">
                            <input class="form-control" id="dr_control_number" name="dr_control_number" placeholder="Control Number"  value="<?php echo $dr_control_number ?>"/>
                        </div>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="dr_date" name="dr_date"  value="<?php echo $dr_date ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="2307">2303/2307</label>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" id="2307" name="2307"  value="<?php echo $checklist_2303 ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="soa">SOA</label>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" id="soa" name="soa"  value="<?php echo $checklist_soa ?>"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="total_material_used">Total Materials Used</label>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" id="total_material_used" name="total_material_used"  value="<?php echo $checklist_materials ?>"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="save_checklist" form="form_checklist">Save checklist</button>
            </div>
            </div>
        </div>
    </div>    
</div>
        
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        let arr_ts_items = [];
        $(document).ready(function() {
            var tbl = document.getElementById('ts_item_table');
            var tbl_rows = tbl.rows.length;
            for(var row=1; row < tbl_rows; row++){
                description = tbl.rows[row].cells[0].innerHTML;
                unit = tbl.rows[row].cells[1].innerHTML;
                quantity = tbl.rows[row].cells[2].innerHTML;
                unitPrice = tbl.rows[row].cells[3].innerHTML;
                item_amount = tbl.rows[row].cells[4].innerHTML;

                arr_ts_items.push([description, unit, quantity, unitPrice]);
                $('#ts_item_array').val(JSON.stringify(arr_ts_items));
            }
            $("#ts_add").on('click', function() {
                description = $('#ts_description').val();
                unit = $('#ts_unit').val();
                quantity = $('#ts_quantity').val();
                unitPrice = $('#ts_unitPrice').val();
                item_amount = parseFloat($('#ts_quantity').val()*$('#ts_unitPrice').val());
                
                arr_ts_items.push([description, unit, quantity, unitPrice]);
                $('#ts_item_array').val(JSON.stringify(arr_ts_items));
                for (x of arr_ts_items) {
                    alert(x);
                }


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
            $('#ts_item_array').val(JSON.stringify(arr_ts_items));
            document.getElementById('ts_item_table').deleteRow(rIndex);
        }

        function computeTotal(){
            
            var totalAmount = 0.0 + parseFloat($('#ts_mobilization').val());
            var tbl = document.getElementById('ts_item_table');
            
            for(var row=1, n=tbl.rows.length; row<n; row++){
                totalAmount += parseFloat(tbl.rows[row].cells[4].innerHTML);
            }

            return totalAmount;

        }


    </script>
  </body>
</html>