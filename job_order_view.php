<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

require_once('db_ops.php');

$db_obj_1 = new Fetch_Specific_Job_Order($_GET['jo_num']);
$jo_information = $db_obj_1->fetchJobOrderInformation();
$all_jo_items = $db_obj_1->fetchJobOrderItems();
$jo_count = $db_obj_1->checkExistingChecklist();

if ($jo_count > 1) {
    $jo_checklist = $db_obj_1->fetchExistingChecklist();
 
    $or_control_number = $jo_checklist['or_cn'];
    $or_date = $jo_checklist['or_date'];
    $ar_control_number = $jo_checklist['ar_cn'];
    $ar_date = $jo_checklist['ar_date'];
    $ws_control_number = $jo_checklist['ws_cn'];
    $ws_date = $jo_checklist['ws_date'];
    $cr_control_number = $jo_checklist['cr_cn'];
    $cr_date = $jo_checklist['cr_date'];
    $dr_control_number = $jo_checklist['dr_cn'];
    $dr_date = $jo_checklist['dr_date'];
    $checklist_2303 = $jo_checklist['checklist_2303_2307'];
    $checklist_soa = $jo_checklist['soa'];
    $checklist_materials = $jo_checklist['total_materials_used'];

    
}


if (isset($_POST['save_checklist']) && !($jo_count > 1)) {
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
        $db_obj_2 = new Add_New_Checklist($_GET['jo_num'], 
                                        $_POST['or_control_number'], $_POST['or_date'], 
                                        $_POST['ar_control_number'], $_POST['ar_date'], 
                                        $_POST['ws_control_number'], $_POST['ws_date'],
                                        $_POST['cr_control_number'], $_POST['cr_date'],
                                        $_POST['dr_control_number'], $_POST['dr_date'],
                                        $_POST['2307'], $_POST['soa'], $_POST['total_material_used']
                                    );
        $new_checklist = $db_obj_2->addNewChecklist();
    }
} else if (isset($_POST['save_checklist']) && ($jo_count > 1)) {
    
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
        <h3 class="display-4">Job Order</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="jo_information">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="jo_number">Job Order Number </label>
                    <input class="form-control" id="jo_number" name="jo_number" value="<?php echo $jo_information['job_order_number']; ?>" />
                </div>
                <div class="form-group col-md-8">
                    <label for="jo_clientName">Client Name </label>
                    <input class="form-control" id="jo_clientName" name="jo_clientName" value="<?php echo $jo_information['client_name']; ?>"/>
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="jo_date" name="jo_date" value="<?php echo $jo_information['date']; ?>" readonly/>
                </div>
                <div class="form-group col-md-8">
                    <label for="jo_representative">Representative</label>
                    <input class="form-control" id="jo_representative" name="jo_representative" value="<?php echo $jo_information['representative']; ?>"/>
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_tin">TIN#</label>
                    <input class="form-control" id="jo_tin" name="jo_tin" value="<?php echo $jo_information['tin_number']; ?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label for="jo_address">Address</label>
                    <input class="form-control" id="jo_address" name="jo_address" value="<?php echo $jo_information['address']; ?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label for="jo_location">Project Location</label>
                    <input class="form-control" id="jo_location" name="jo_location" value="<?php echo $jo_information['project_location']; ?>"/>
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_mobilization">Mobilization</label>
                    <input class="form-control" id="jo_mobilization" name="jo_mobilization" value="<?php echo $jo_information['mobilization']; ?>"/>
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_cod">COD(Days)</label>
                    <select class="form-control" id="jo_cod" name="jo_cod" value="<?php echo $jo_information['terms_of_payment']; ?>">
                    <option>30</option>
                    <option>60</option>
                    <option>90</option>
                    <option>150</option>
                    <option>180</option>
                    </select>
                </div> 
            </div>
            <hr />
            
            <div class="form-row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_creator">Created By:</label>
                    <input class="form-control" id="jo_creator" name="jo_creator" value="<?php echo $jo_information['employee_name']; ?>" readonly />
                </div>
                
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_totalPayment">Total Payment</label>
                    <input type="number" class="form-control" id="jo_totalPayment" value="<?php echo $jo_information['jo_sum']+$jo_information['mobilization']; ?>" readonly/>
                </div>
            </div>
            <input type="hidden" id="jo_item_array" name="jo_item_array">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="jo_save" name="jo_save">Save</button>
                </div>
                <div class="form-group col-md-3">
                <button type="button" class="font-control btn btn-info" data-toggle="modal" data-target="#checklistModal">
                    Checklist
                </button>
                </div>
                <div class="form-group col-md-3">
                    <a href="projects.php" type="button" class="form-control btn btn-danger" id="jo_cancel" name="jo_cancel">Cancel</a>
                </div>
            </div>
            
        </form>
        
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="jo_items">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="jo_description">Description</label>
                    <input class="form-control" id="jo_description" name="jo_description" />
                </div>
                <div class="form-group col-md-2 col-sm-6">
                    <label for="jo_unit">Unit</label>
                    <input class="form-control" id="jo_unit" name="jo_unit" />
                </div>
                <div class="form-group col-md-2 col-sm-6">
                    <label for="jo_quantity">Qty.</label>
                    <input type="number" class="form-control" id="jo_quantity" name="jo_quantity"/>
                </div>
                <div class="form-group col-md-2">
                    <label for="jo_unitPrice">Unit Price</label>
                    <input type="number" class="form-control" id="jo_unitPrice" name="jo_unitPrice" />
                </div>
                <div class="form-group col-md-1">
                    <label for="jo_add">&nbsp</label>
                    <button type="button" class="form-control btn btn-primary" id="jo_add" name="jo_add">Add</button>
                </div>
            </div>
            <table class="table table-striped table-sm" id="jo_item_table">
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
                        foreach ($all_jo_items as $job_order_item) {
                    ?>
                        <tr>
                            <td><?php echo $job_order_item['description'] ?></td>
                            <td><?php echo $job_order_item['unit'] ?></td>
                            <td><?php echo $job_order_item['quantity'] ?></td>
                            <td><?php echo $job_order_item['unit_price'] ?></td>
                            <td><?php echo $job_order_item['quantity']*$job_order_item['unit_price'] ?></td>
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

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        let arr_jo_items = [];
        $(document).ready(function() {
            let ctr1 = 0;
            
            $("#jo_add").on('click', function() {
                description = $('#jo_description').val();
                unit = $('#jo_unit').val();
                quantity = $('#jo_quantity').val();
                unitPrice = $('#jo_unitPrice').val();
                item_amount = parseFloat($('#jo_quantity').val()*$('#jo_unitPrice').val());
                
                arr_jo_items.push([description, unit, quantity, unitPrice, item_amount]);

                for (x of arr_jo_items) {
                    alert(x);
                }


                new_row = "<tr> \
                            <td>"+description+"</td> \
                            <td>"+unit+"</td> \
                            <td>"+quantity+"</td> \
                            <td>"+unitPrice+"</td> \
                            <td>"+item_amount+"</td> \
                            <td><button type='button' class='btn btn-outline-danger btn-sm' onClick='deleteRow(this)'>Delete</button></td>";
                            
                jo_items_tbl = $('table tbody');
                jo_items_tbl.append(new_row);
                $('#jo_totalPayment').val(computeTotal);
                $('#jo_description').val("");
                $('#jo_unit').val("");
                $('#jo_quantity').val("");
                $('#jo_unitPrice').val("");
                $('#jo_item_array').val(JSON.stringify(arr_jo_items));
            });
        });

        function deleteRow(cell){
            var row = $(cell).parents('tr');
            var rIndex = row[0].rowIndex;

            arr_jo_items.splice(rIndex-1, 1);

            document.getElementById('jo_item_table').deleteRow(rIndex);
        }

        function computeTotal(){
            
            var totalAmount = 0.0 + parseFloat($('#jo_mobilization').val());
            var tbl = document.getElementById('jo_item_table');
            
            for(var row=1, n=tbl.rows.length; row<n; row++){
                totalAmount += parseFloat(tbl.rows[row].cells[4].innerHTML);
            }

            return totalAmount;

        }


    </script>
  </body>
</html>