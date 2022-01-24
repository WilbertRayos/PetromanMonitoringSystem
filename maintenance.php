<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

require_once('db_ops.php');

$obj_report_generation = new Report_Generation();
$obj_maintenance = new Maintenance;
//== Default Values==//

$all_roles = $obj_maintenance->fetchAllAccountType();

// Default Company
$allCompany = $obj_maintenance->fetchAllCompany();

$allUnits = $obj_maintenance->fetchAllUnits();

$allItems = $obj_maintenance->fetchAllItems();

$allUsers = $obj_maintenance->fetchAllUsers();


if(isset($_POST['btn_add'])) {
    if($_POST['operation'] == "add") {
        if(!isset($_POST['add_new_item']) || empty($_POST['add_new_item'])) {
            echo "<script>alert('Please fill up the new item');</script>";
        } else {
            $obj_maintenance->addNewItem($_POST['add_category'], $_POST['add_new_item']);
        }
    } else if ($_POST['operation'] == "delete") {
        // echo "<script>alert(2);</script>";
        
        if ($_POST['delete_category'] == "Account Type") {
            $obj_maintenance->deleteItem($_POST['delete_category'], $_POST['del_role']);
        } else if ($_POST['delete_category'] == "Company Name") {
            $obj_maintenance->deleteItem($_POST['delete_category'], $_POST['del_company']);
        } else if ($_POST['delete_category'] == "Item Description") {
            $obj_maintenance->deleteItem($_POST['delete_category'], $_POST['del_item']);
        } else if ($_POST['delete_category'] == "Item Unit") {
            $obj_maintenance->deleteItem($_POST['delete_category'], $_POST['del_unit']);
        }
    }
    header("Location: maintenance.php");
}

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
    <title>Maintenance</title>
   
  </head>
  <body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h3 class="display-4 my-4 page-title">Maintenance</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="form_maintenance">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="operation" id="add" value="add" checked="checked">
                        <label class="form-check-label" for="add">
                            Add
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="operation" id="delete" value="delete">
                        <label class="form-check-label" for="delete">
                            Delete
                        </label>
                    </div>
                </div>
            </div>
            <div id="maintenance_add">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="add_category">Category</label>
                        <select class="form-control" id="add_category" name="add_category">
                            <option>Account Type</option>
                            <option>Company Name</option>
                            <option>Item Description</option>
                            <option>Item Unit</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="add_new_item">New Item</label>
                        <input class="form-control" id="add_new_item" name="add_new_item">
                    </div>
                </div>
            </div> 
            <div id="maintenance_delete">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="delete_category">Category</label>
                        <select class="form-control" id="delete_category" name="delete_category">
                            <option>Account Type</option>
                            <option>Company Name</option>
                            <option>Item Description</option>
                            <option>Item Unit</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12" id="delete_role">
                        <label for="del_role">Account Type to delete</label>
                        <!-- <input class="form-control" id="delete_new_item" name="delete_new_item"> -->
                        <select class="form-control" id="del_role" name="del_role">
                            <?php
                                foreach($all_roles as $role) {
                            ?>
                                <option><?php echo $role['role_desc']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        
                    </div>
                    <div class="form-group col-md-12" id="delete_company">
                        <label for="del_company">Company to delete</label>
                        <!-- <input class="form-control" id="delete_new_item" name="delete_new_item"> -->
                        <select class="form-control" id="del_company" name="del_company">
                            <?php
                                foreach($allCompany as $company) {
                            ?>
                                <option><?php echo $company['company_desc']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        
                    </div>
                    <div class="form-group col-md-12" id="delete_item_desc">
                        <label for="del_item">Item to delete</label>
                        <!-- <input class="form-control" id="delete_new_item" name="delete_new_item"> -->
                        <select class="form-control" id="del_item" name="del_item">
                            <?php
                                foreach($allItems as $item) {
                            ?>
                                <option><?php echo $item['product_desc']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-12" id="delete_unit">
                        <label for="del_unit">Unit to delete</label>
                        <!-- <input class="form-control" id="delete_new_item" name="delete_new_item"> -->
                        <select class="form-control" id="del_unit" name="del_unit">
                            <?php
                                foreach($allUnits as $unit) {
                            ?>
                                <option><?php echo $unit['unit_desc']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- <div class="form-row">
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-danger" form="form_maintenance" id="btn_delete" name="btn_delete">Delete</button>
                    </div>
                </div> -->
            </div> 
            <div class="form-row">
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success" form="form_maintenance" id="btn_add" name="btn_add">Add</button>
                    </div>
            </div>
        </form>

        
       
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
  </body>
  <script>
    
    $(document).ready(function() {
        hideDelete();
        displayAdd();
        $('input[type=radio][name="operation"]').on('change', function() {
            var btn = document.querySelector('#btn_add');
            if(this.value == "add") {
                hideDelete();
                displayAdd();
                
                btn.innerHTML = "Add";
                btn.style.backgroundColor = "#42ba96";
            } else if (this.value == "delete") {
                hideAdd();
                displayDelete();
                btn.innerHTML = "Delete";
                btn.style.backgroundColor = "#df4759";
            }
        });
        displayRole();
        hideCompany();
        hideItem();
        hideUnit();
        $('#delete_category').on('change', function() {
            
            if (this.value == "Account Type") {
                hideCompany();
                hideItem();
                hideUnit();
                displayRole();
            } else if(this.value == "Company Name") {
                hideRole();
                hideItem();
                hideUnit();
                displayCompany();
            } else if (this.value == "Item Description") {
                hideRole();
                hideCompany();
                hideUnit();
                displayItem();
            } else if (this.value == "Item Unit") {
                hideRole();
                hideCompany();
                hideItem();
                displayUnit();
            }
        });
    });

    function displayRole() {
        var categRole = document.getElementById('delete_role');
        categRole.style.display = "block";
    }

    function hideRole() {
        var categRole = document.getElementById('delete_role');
        categRole.style.display = "none";
    }

    function displayUnit() {
        var categUnit = document.getElementById('delete_unit');
        categUnit.style.display = "block";
    }

    function hideUnit() {
        var categUnit = document.getElementById('delete_unit');
        categUnit.style.display = "none";
    }

    function displayItem() {
        var categItem = document.getElementById('delete_item_desc');
        categItem.style.display = "block";
    }

    function hideItem() {
        var categItem = document.getElementById('delete_item_desc');
        categItem.style.display = "none";
    }

    function displayCompany() {
        var categCompany = document.getElementById('delete_company');
        categCompany.style.display = "block";
    }

    function hideCompany() {
        var categCompany = document.getElementById('delete_company');
        categCompany.style.display = "none";
    }

    function displayAdd() {
        var addBlock = document.getElementById('maintenance_add');
        addBlock.style.display = "block";
    }

    function hideAdd() {
        var addBlock = document.getElementById('maintenance_add');
        addBlock.style.display = "none";
    }

    function displayDelete() {
        var deleteBlock = document.getElementById('maintenance_delete');
        deleteBlock.style.display = "block";
    }

    function hideDelete() {
        var deleteBlock = document.getElementById('maintenance_delete');
        deleteBlock.style.display = "none";
    }

  </script>
</html>