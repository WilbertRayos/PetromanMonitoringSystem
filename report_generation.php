<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}

require_once('db_ops.php');

$obj_report_generation = new Report_Generation();
$obj_maintenance = new Maintenance;
$obj_history =  new History;
//== Default Values==//
// Default Company
$allCompany = $obj_maintenance->fetchAllCompany();

// Default Users
$allEmail = $obj_maintenance->fetchAllUsers();

$filtered_items = array();
if (isset($_POST['btn_filter'])) {
    if ((!isset($_POST['start_date']) || empty($_POST['start_date']))
    && (!isset($_POST['end_date']) || empty($_POST['end_date']))
    && (!isset($_POST['company_name']) || empty($_POST['company_name']))
    && (!isset($_POST['creator']) || empty($_POST['creator']))) {
        echo "<script>alert('Please enter at least 1 criteria to filter');</script>";
    } else {
        $obj_report_generation->setStartDate($_POST['start_date']);
        $obj_report_generation->setEndDate($_POST['end_date']);
        $obj_report_generation->setCompanyName($_POST['company_name']);
        $obj_report_generation->setCreator($_POST['creator']);
        $filtered_items = $obj_report_generation->fetchFilterValues();
    }
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
    <link rel="stylesheet" href="css/table_sorter.css">
    <title>Report</title>
    <style id="table_style" type="text/css">
        table
        {
            border: 1px solid #ccc;
            border-collapse: collapse;
            font-family: Arial;
            font-size: 10pt;
        }
        table th
        {
            background-color: #F7F7F7;
            color: #333;
            font-weight: bold;
        }
        table th, table td
        {
            padding: 5px;
            border: 1px solid #ccc;
        }
    </style>
  </head>
  <body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h3 class="display-4 my-4 page-title">Report Generation</h3>
        <div class="row md-mt-3 justify-content-end">
            <div class="col-4">
                <div class="input-group mb-3">
                    <!-- <input type="text" class="form-control" placeholder="Job Order #" aria-label="Job Order #" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div> -->
                </div>
            </div>
        </div>

        <form action="<?php echo $path_parts['basename'];?>" method="POST" id="report_filter">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="form-group col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="company_name">Company Name</label>
                    <select class="form-control" id="company_name" name="company_name">
                        <option></option>
                        <?php 
                            foreach ($allCompany as $company) {
                        ?>
                        <option><?php echo $company['company_desc']?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="creator">Creator</label>
                    <select class="form-control" id="creator" name="creator">
                        <option></option>
                        <?php
                            foreach($allEmail as $employee) {
                        ?>
                        <option><?php echo $employee['employee_email']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- <div class="form-row">
                <div class="form-group col-md-3">                   
                    <label for="sort_by">Sort By</label>
                    <select class="form-control" id="sort_by" name="sort_by">
                        <option>Job Order Number</option>
                        <option>Company Name</option>
                        <option>Representative</option>
                        <option>Contact Number</option>
                        <option>Address</option>
                        <option>Date</option>
                        <option>Description</option>
                        <option>Unit</option>
                        <option>Quantity</option>
                        <option>Unit Price</option>
                        <option>Total Amount</option>
                    </select>
                </div>
                <div class="form-group col-md-4 mt-4">                   
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sort" id="asc" value="asc" checked>
                        <label class="form-check-label" for="asc">
                            Ascending
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sort" id="desc" value="desc">
                        <label class="form-check-label" for="desc">
                            Descending
                        </label>
                    </div>
                </div>
            </div> -->
            <div class="form-row">
                <div class="form-group col-md-8">
                    <button type="submit" class="btn btn-success float-right" id="btn_filter" name="btn_filter" form="report_filter">Filter</button>
                </div>
                <div class="form-group col-md-4">
                    <button type="button" class="btn btn-danger float-right" id="btn_print" name="btn_print" form="btn_print">Print</button>
                </div>
            </div>
        </form>


        
        <table class="table table-striped table-sortable" id="printTable">
            <thead class="thead-dark">
                <tr>
                    <th>Employee Name</th>
                    <th>Order Number</th>
                    <th>Company Name</th>
                    <th>Representative</th>
                    <th>Contact #</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($filtered_items as $row) {
                ?>
                    <tr>
                        <td><?php echo $row['employee_name']; ?></td>
                        <td><?php echo $row['job_order_number']; ?></td>
                        <td><?php echo $row['client_name']; ?></td>
                        <td><?php echo $row['representative']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo number_format($row['unit_price'],2); ?></td>
                        <td><?php echo number_format($row['amount'],2); ?></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
  </body>
  <script>
    //   $('a#job_order_number').on('click', function() {
    //     var jo_num = $(this).text();
    //     var loc = window.location.pathname;
    //     var dir = loc.substring(0, loc.lastIndexOf('/'));
    //     window.location.href = dir+"/finance_job_order_view.php?jo_num="+jo_num;
    //   });

      $('#btn_print').on('click',function(){
        var divToPrint=document.getElementById("printTable");
        newWin= window.open("");
        var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/'));
        newWin.document.write("<img src='"+dir+"/img/logo.png'>");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();

    });

  </script>
</html>