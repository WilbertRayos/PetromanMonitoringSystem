<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

if (isset($_POST['jo_save'])) {
    $db_obj1 = new Add_New_Job_Order;
    $db_obj1->setJobOrderNumber($_POST['jo_number']);
    $db_obj1->setClientName($_POST['jo_clientName']);
    $db_obj1->setDate($_POST['jo_date']);
    $db_obj1->setRepresentative($_POST['jo_representative']);
    $db_obj1->setContactNumber($_POST['jo_contact']);
    $db_obj1->setTinNumber($_POST['jo_tin']);
    $db_obj1->setAddress($_POST['jo_address']);
    $db_obj1->setProjectLocation($_POST['jo_location']);
    $db_obj1->setTermsOfPayment($_POST['jo_cod']);
    $db_obj1->setMobilization($_POST['jo_mobilization']);
    $db_obj1->setEmployeeID($_SESSION['employee_id']);
    $arr = json_decode($_POST['jo_item_array']);
    try{
        $db_obj1->addNewJobOrder();
        foreach($arr as $items[]) {
            foreach($items as $item) {
                $db_obj1->addJobOrderItems($item[0],$item[1],$item[2],$item[3]);
            }
        }
    } catch(Exception $e) {
        
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
                    <input class="form-control" id="jo_number" name="jo_number" />
                </div>
                <div class="form-group col-md-8">
                    <label for="jo_clientName">Client Name </label>
                    <input class="form-control" id="jo_clientName" name="jo_clientName" />
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="jo_date" name="jo_date" value="<?php echo date('m/d/Y');?>" readonly/>
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_representative">Representative</label>
                    <input class="form-control" id="jo_representative" name="jo_representative" />
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_contact">Contact Number</label>
                    <input class="form-control" id="jo_contact" name="jo_contact" />
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_tin">TIN#</label>
                    <input class="form-control" id="jo_tin" name="jo_tin" />
                </div>
                <div class="form-group col-md-12">
                    <label for="jo_address">Address</label>
                    <input class="form-control" id="jo_address" name="jo_address" />
                </div>
                <div class="form-group col-md-12">
                    <label for="jo_location">Project Location</label>
                    <input class="form-control" id="jo_location" name="jo_location" />
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_mobilization">Mobilization</label>
                    <input class="form-control" id="jo_mobilization" name="jo_mobilization" />
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_cod">COD(Days)</label>
                    <select class="form-control" id="jo_cod" name="jo_cod">
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
                    <input class="form-control" id="jo_creator" name="jo_creator" value="<?php echo $_SESSION['employee_fName']." ".$_SESSION['employee_mName']." ".$_SESSION['employee_lName']?>" readonly />
                </div>
                
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_totalPayment">Total Payment</label>
                    <input type="number" class="form-control" id="jo_totalPayment" readonly/>
                </div>
            </div>
            <input type="hidden" id="jo_item_array" name="jo_item_array">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="jo_save" name="jo_save">Save</button>
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
                    <input type="number" min="0" onkeyup="if(this.value<0)this.value=0"
    onblur="if(this.value<0)this.value=0" class="form-control" id="jo_quantity" name="jo_quantity"/>
                </div>
                <div class="form-group col-md-2">
                    <label for="jo_unitPrice">Unit Price</label>
                    <input type="number" min="0" class="form-control" id="jo_unitPrice" name="jo_unitPrice" />
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
        let arr_jo_items = [];
        $(document).ready(function() {
            let ctr1 = 0;
            
            $("#jo_add").on('click', function() {
                description = $('#jo_description').val();
                unit = $('#jo_unit').val();
                quantity = $('#jo_quantity').val();
                unitPrice = $('#jo_unitPrice').val();
                item_amount = parseFloat($('#jo_quantity').val()*$('#jo_unitPrice').val());
                if (!description) {
                    alert("Item description must not be empty");
                } else if (!unit) {
                    alert("Item unit must not be empty");
                } else if ((!quantity) || quantity <= 0) {
                    alert("Quantity must be greater than 0");
                } else if ((!unitPrice) || unitPrice <= 0) {
                    alert("Unit price must be greater than 0");
                } else {
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
                }
                // description = $('#jo_description').val();
                // unit = $('#jo_unit').val();
                // quantity = $('#jo_quantity').val();
                // unitPrice = $('#jo_unitPrice').val();
                // item_amount = parseFloat($('#jo_quantity').val()*$('#jo_unitPrice').val());
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