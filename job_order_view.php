<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

require_once('db_ops.php');

$obj_maintenance = new Maintenance;


$job_order_number = $_GET['jo_num'];
$db_obj_1 = new Fetch_Specific_Job_Order($_GET['jo_num']);
$jo_information = $db_obj_1->fetchJobOrderInformation();
$all_jo_items = $db_obj_1->fetchJobOrderItems();

if (isset($_POST['jo_update'])){
    if (is_numeric($_POST['jo_contact']) != 1) {

        echo "<script>alert('Please Fill-up Contact Number Properly');</script>";
    } else {
        $arr = json_decode($_POST['jo_item_array']);

        $db_obj_updateJobOrder = new Update_Job_Order($job_order_number);
        $db_obj_updateJobOrder->deleteJobOrderItem($arr);
        $db_obj_updateJobOrder->updateJobOrderItems($arr);
        $db_obj_updateJobOrder->updateJobOrderInformation($_POST['jo_number'], $_POST['jo_clientName'], $_POST['jo_representative'], $_POST['jo_contact'],
        $_POST['jo_address'],$_POST['jo_date'],$_POST['jo_tin'],$_POST['jo_location'],$_POST['jo_cod'],$_POST['jo_mobilization']);
        


        header('Location: projects.php');
    }
    
}



// Checklist
$jo_count = $db_obj_1->checkExistingChecklist();
// If checklist already exists
if ($jo_count > 0) {
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


if (isset($_POST['save_checklist']) && !($jo_count > 0)) {
    // if (!isset($_POST['or_control_number']) || empty($_POST['or_control_number']) || !isset($_POST['or_date']) || empty($_POST['or_date'])) {
    //     echo "<script>alert('Fill-up OR information');</script>";
    // } else if (!isset($_POST['ar_control_number']) || empty($_POST['ar_control_number']) || !isset($_POST['ar_date']) || empty($_POST['ar_date'])) {
    //     echo "<script>alert('Fill-up AR information');</script>";
    // } else if (!isset($_POST['ws_control_number']) || empty($_POST['ws_control_number']) || !isset($_POST['ws_date']) || empty($_POST['ws_date'])) {
    //     echo "<script>alert('Fill-up WS information');</script>";
    // } else if (!isset($_POST['cr_control_number']) || empty($_POST['cr_control_number']) || !isset($_POST['cr_date']) || empty($_POST['cr_date'])) {
    //     echo "<script>alert('Fill-up CR information');</script>";
    // } else if (!isset($_POST['dr_control_number']) || empty($_POST['dr_control_number']) || !isset($_POST['dr_date']) || empty($_POST['dr_date'])) {
    //     echo "<script>alert('Fill-up DR information');</script>";
    // } else if (!isset($_POST['2307']) || empty($_POST['2307'])) {
    //     echo "<script>alert('Fill-up 2303/2307 information');</script>";
    // } else if (!isset($_POST['soa']) || empty($_POST['soa'])) {
    //     echo "<script>alert('Fill-up SOA information');</script>";
    // } else if (!isset($_POST['total_material_used']) || empty($_POST['total_material_used'])) {
    //     echo "<script>alert('Fill-up total_material_used information');</script>";
    // } else {
        $db_obj_2 = new Add_New_Checklist($_GET['jo_num'], 
                                        $_POST['or_control_number'], $_POST['or_date'], 
                                        $_POST['ar_control_number'], $_POST['ar_date'], 
                                        $_POST['ws_control_number'], $_POST['ws_date'],
                                        $_POST['cr_control_number'], $_POST['cr_date'],
                                        $_POST['dr_control_number'], $_POST['dr_date'],
                                        $_POST['2307'], $_POST['soa'], $_POST['total_material_used']
                                    );
        $new_checklist = $db_obj_2->addNewChecklist();
        header("Refresh:0");
    // }
} else if (isset($_POST['save_checklist']) && ($jo_count >= 1)) {
    // if (!isset($_POST['or_control_number']) || empty($_POST['or_control_number']) || !isset($_POST['or_date']) || empty($_POST['or_date'])) {
    //     echo "<script>alert('Fill-up OR information');</script>";
    // } else if (!isset($_POST['ar_control_number']) || empty($_POST['ar_control_number']) || !isset($_POST['ar_date']) || empty($_POST['ar_date'])) {
    //     echo "<script>alert('Fill-up AR information');</script>";
    // } else if (!isset($_POST['ws_control_number']) || empty($_POST['ws_control_number']) || !isset($_POST['ws_date']) || empty($_POST['ws_date'])) {
    //     echo "<script>alert('Fill-up WS information');</script>";
    // } else if (!isset($_POST['cr_control_number']) || empty($_POST['cr_control_number']) || !isset($_POST['cr_date']) || empty($_POST['cr_date'])) {
    //     echo "<script>alert('Fill-up CR information');</script>";
    // } else if (!isset($_POST['dr_control_number']) || empty($_POST['dr_control_number']) || !isset($_POST['dr_date']) || empty($_POST['dr_date'])) {
    //     echo "<script>alert('Fill-up DR information');</script>";
    // } else if (!isset($_POST['2307']) || empty($_POST['2307'])) {
    //     echo "<script>alert('Fill-up 2303/2307 information');</script>";
    // } else if (!isset($_POST['soa']) || empty($_POST['soa'])) {
    //     echo "<script>alert('Fill-up SOA information');</script>";
    // } else if (!isset($_POST['total_material_used']) || empty($_POST['total_material_used'])) {
    //     echo "<script>alert('Fill-up total_material_used information');</script>";
    // } else {
        $db_obj_3 = new Update_Checklist(
            $_GET['jo_num'], $_POST['or_control_number'], $_POST['or_date'],
            $_POST['ar_control_number'], $_POST['ar_date'], $_POST['ws_control_number'], $_POST['ws_date'],
            $_POST['cr_control_number'], $_POST['cr_date'], $_POST['dr_control_number'], $_POST['dr_date'],
            $_POST['2307'], $_POST['soa'], $_POST['total_material_used']
        );
        $db_obj_3->updateChecklist();
        header("Refresh:0");
    // }
}

$db_obj_4 = new Job_Order_Phases($_GET['jo_num']);
$jo_phases = $db_obj_4->fetchAllJOPhases();
if (isset($_POST['submit_phase'])) {
    // Image
    $file = $_FILES['project_phase_picture'];
    $fileName = $file['name'];
    $fileTempName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];
    //Phase Number
    $phase = $_POST['project_phase'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg','jpeg','png');

    // If image file extension is correct
    if(in_array($fileActualExt, $allowed)) {
        // If no error encountered in the image
        if($fileError === 0) {
            if($fileSize < 5000000) {
                try {
                    $fileNewName = uniqid('',true).".".$fileActualExt;
                    $fileDestination = 'phases_pictures/'.$fileNewName;
                    move_uploaded_file($fileTempName, $fileDestination);
                    $db_obj_4->addNewPhase($phase, $fileNewName);

                    header("Refresh:0");
                }catch (PDOException $e) {

                    echo "<script>alert('Unexpected Error Occured');</script>";
                }
                
            }
        }
    }

   
}

if (isset($_POST['jo_delete'])) {
    $obj_delete_jo = new Delete_Specific_Job_Order($_POST["jo_number"]);
    $obj_delete_jo->deleteJobOrder();
    
}

if (isset($_POST["btn_delete_phase"])) {
    if (!isset($_POST["delete_phase"]) || empty($_POST["delete_phase"])) {
        echo "<script>alert('Please enter the phase Number you want to delete');</script>";
    } else {
        $db_obj_4->deletePhase($job_order_number, $_POST["delete_phase"]);
        header('Location: projects.php');
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" media="all">
    <link rel="stylesheet" href="css/main.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/print.css" type="text/css" media="print">
    <script src="js/main.js"></script>
    <title>View Job Order</title>
    <style>
        #phase_title {
            text-align: center;
            font-weight: bold;
        }
       #phase_id {
           color: white;
       }
    </style>
  </head>
  <body>
   
    <?php require('navbar.php');?>

    <div class="container">
        <div id="print_main_info">

        
        <h3 class="display-4 my-4 page-title">Job Order</h3>
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="jo_information">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="jo_number">Job Order Number </label>
                    <input class="form-control" id="jo_number" name="jo_number" value="<?php echo $jo_information['job_order_number']; ?>" />
                </div>
                <div class="form-group col-md-8">
                    <label for="jo_clientName">Client Name </label>
                    <!-- <input class="form-control" id="jo_clientName" name="jo_clientName" value="<?php echo $jo_information['client_name']; ?>"/> -->
                    <select class="form-control" id="jo_clientName" name="jo_clientName" >
                        <?php
                            
                            $all_company = $obj_maintenance->fetchAllCompany();
                            foreach ($all_company as $company) {
                        ?>
                            
                            <option value="<?php echo $company['company_desc']; ?>" <?php if($jo_information['client_name'] == $company['company_desc']) echo 'selected="selected"';?>><?php echo $company['company_desc']; ?></option>
                        <?php
                            }
                        ?>

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="jo_date" name="jo_date" value="<?php echo $jo_information['date']; ?>" readonly/>
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_representative">Representative</label>
                    <input class="form-control" id="jo_representative" name="jo_representative" value="<?php echo $jo_information['representative']; ?>"/>
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_representative">Contact Number</label>
                    <input class="form-control" id="jo_contact" name="jo_contact" value="<?php echo $jo_information['contact_number']; ?>"/>
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
                    <input class="form-control" id="jo_mobilization" name="jo_mobilization" value="<?php echo number_format($jo_information['mobilization'],2); ?>"/>
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="jo_cod">Terms of Payment</label>
                    <select class="form-control" id="jo_cod" name="jo_cod" value="<?php echo $jo_information['terms_of_payment']; ?>">
                        <option value="COD" <?php if($jo_information['terms_of_payment'] == "COD") echo 'selected="selected"';?>>COD</option>
                        <option value="30 Days" <?php if($jo_information['terms_of_payment'] == "30 Days") echo 'selected="selected"';?>>30 Days</option>
                        <option value="60 Days" <?php if($jo_information['terms_of_payment'] == "60 Days") echo 'selected="selected"';?>>60 Days</option>
                        <option value="90 Days" <?php if($jo_information['terms_of_payment'] == "90 Days") echo 'selected="selected"';?>>90 Days</option>
                        <option value="150 Days" <?php if($jo_information['terms_of_payment'] == "150 Days") echo 'selected="selected"';?>>150 Days</option>
                        <option value="180 Days" <?php if($jo_information['terms_of_payment'] == "180 Days") echo 'selected="selected"';?>>180 Days</option>
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
                    <input class="form-control" id="jo_totalPayment" value="<?php echo number_format($jo_information['jo_sum']+$jo_information['mobilization'],2); ?>" readonly/>
                </div>
            </div>

</div>
            <input type="hidden" id="jo_item_array" name="jo_item_array">
            <div class="form-row">
                    <?php 
                        if ($_SESSION['employee_role'] == "Admin") {
                    ?>
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="jo_update" name="jo_update" form="jo_information">Update</button>
                </div>
                    <?php 
                        }
                    ?>
                <div class="form-group col-md-2">
                    <button type="button" class="form-control btn btn-info" id="jo_checklist" data-toggle="modal" data-target="#checklistModal">
                        Checklist
                    </button>
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="form-control btn btn-primary" id="jo_phases" data-toggle="modal" data-target="#phasesModal">
                        Phases
                    </button>
                </div> 
                <div class="form-group col-md-2">
                    <button type="button" class="form-control btn btn-success" id="print_page" name="print_page" form="jo_information">
                        Print
                    </button>
                </div> 
                <div class="form-group col-md-3">
                    <a href="projects.php" type="button" class="form-control btn btn-danger" id="jo_cancel" name="jo_cancel">Cancel</a>
                </div> 
                <div class="form-group col-md-2">
                    <!-- <button type="submit" class="form-control btn btn-outline-danger" id="jo_delete" name="jo_delete" form="jo_information">Delete</button> -->
                </div> 
                
            </div>
            
        </form>
        
        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="jo_items">
            <div class="form-row" id="jo_items_div">
                <div class="form-group col-md-5">
                    <label for="jo_description">Description</label>
                    <input class="form-control" id="jo_description" name="jo_description" />
                </div>
                <div class="form-group col-md-2 col-sm-6">
                    <label for="jo_unit">Unit</label>
                    <!-- <select class="form-control" id="jo_unit" name="jo_unit">
                        <option selected="selected">SQM</option>
                        <option>PC</option>
                        <option>BAGS</option>
                        <option>KG</option>
                        <option>BOX</option>
                    </select> -->
                    <select class="form-control" id="jo_unit" name="jo_unit">
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
                            <td><?php echo number_format($job_order_item['quantity'],2) ?></td>
                            <td><?php echo number_format($job_order_item['unit_price'],2) ?></td>
                            <td><?php echo number_format($job_order_item['quantity']*$job_order_item['unit_price'],2) ?></td>
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
                            <input class="form-control" id="or_control_number" name="or_control_number" placeholder="Control Number" value="<?php echo $or_control_number; ?>"/>
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

        

        <!-- Phases Modal -->
        <div class="modal fade" id="phasesModal" tabindex="-1" role="dialog" aria-labelledby="phasesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="phasesModalLabel">Phases</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="project_phases" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="project_phase">Phase Number</label>
                                    <select class="form-control" id="project_phase" name="project_phase">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 col-sm-6">
                                    <label for="project_phase_picture">Upload Picture</label>
                                    <input type="file" class="form-control-file" id="project_phase_picture" name="project_phase_picture">
                                </div>
                            </div>  
                        </form>    
                        <hr />
                        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="delete_phase_form" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="delete_phase">Phase Number</label>
                                    <input type="number" class="form-control" id="delete_phase" name="delete_phase">
                                    <button type="submit" class="btn btn-danger mt-1" name="btn_delete_phase" form="delete_phase_form">Delete</button>
                                </div>
                            </div>  
                        </form>  
                        <hr /> 
                        <h4 id="phase_title">Phases</h4>
                        <?php
                                foreach($jo_phases as $jo_phase) {
                                    
                        ?>
                        <div id="phase_id">
                            Phase ID: <?php echo $jo_phase['project_phases_id']; ?>
                        </div>
                        <div>
                            Phase Stage: <?php echo $jo_phase['stage']; ?> -
                            <?php 
                                if ($jo_phase['stage'] == 1) {
                                    echo "Area upon Checking";
                                } else if ($jo_phase['stage'] == 2) {
                                    echo "Starting the Project";
                                } else if ($jo_phase['stage'] == 3) {
                                    echo "50% Completion of the Project";
                                } else if ($jo_phase['stage'] == 4) {
                                    echo "Finish Project";
                                } else {
                                    echo "";
                                }
                            ?>
                        </div>
                            <div class="col-md-12">
                                    <img src="phases_pictures/<?php echo $jo_phase['image']; ?>" class="img-thumbnail"/>
                            </div>
                            
                        <?php
                            }
                                    
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submit_phase" form="project_phases">Save</button>
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
        let arr_jo_items = [];
        $(document).ready(function() {
            var tbl = document.getElementById('jo_item_table');
            var tbl_rows = tbl.rows.length;
            for(var row=1; row < tbl_rows; row++){
                description = tbl.rows[row].cells[0].innerHTML;
                unit = tbl.rows[row].cells[1].innerHTML;
                quantity = tbl.rows[row].cells[2].innerHTML;
                unitPrice = tbl.rows[row].cells[3].innerHTML;
                item_amount = tbl.rows[row].cells[4].innerHTML;

                arr_jo_items.push([description, unit, quantity, unitPrice]);
                $('#jo_item_array').val(JSON.stringify(arr_jo_items));
            }
            $("#jo_add").on('click', function() {
                description = $('#jo_description').val();
                unit = $('#jo_unit').val();
                quantity = $('#jo_quantity').val();
                unitPrice = $('#jo_unitPrice').val();
                item_amount = parseFloat($('#jo_quantity').val()*$('#jo_unitPrice').val());
                
                arr_jo_items.push([description, unit, quantity, unitPrice]);
                $('#jo_item_array').val(JSON.stringify(arr_jo_items));

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

            $("#print_page").on('click', function() {
                // var divToPrint=document.getElementsByClassName('container')[0].innerHTML;
                // newWin= window.open("");
                // newWin.document.write("<img src='img/logo.png'>");
                // newWin.document.write(divToPrint);
                // newWin.print();
                // newWin.close();
                var mywindow = window.open();
                var divToPrint = document.getElementById("print_main_info");
                var tableToPrint = document.getElementById("jo_item_table");
                mywindow.document.write('<html><head><title>my div</title>');
                // mywindow.document.write('<link rel="stylesheet" href="css/main.css" type="text/css" />');
                mywindow.document.write('<link rel="stylesheet" href="css/print.css" type="text/css" media="print"/>');
                mywindow.document.write('<style>');
                mywindow.document.write('body {margin: 20px; font-size: 24px;} .form-control {font-size:24px !important} table {font-size: 26px; text-align:center; width:70%; margin-left:15%; margin-right:15%; margin-top: 10%;} @media print {.btn {display: none;}');
                mywindow.document.write('</style>');
                mywindow.document.write('</head><body >');
                mywindow.document.write("<img src='img/logo.png'>");
                mywindow.document.write(divToPrint.innerHTML);
                mywindow.document.write(tableToPrint.outerHTML);
                mywindow.document.write('</body></html>');

                mywindow.print();
                mywindow.close();

            });
        });

        function deleteRow(cell){
            var row = $(cell).parents('tr');
            var rIndex = row[0].rowIndex;
            arr_jo_items.splice(rIndex-1, 1);
            $('#jo_item_array').val(JSON.stringify(arr_jo_items));
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