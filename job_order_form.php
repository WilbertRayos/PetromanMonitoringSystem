<?php
     session_start();
     if(!isset($_SESSION['loggedIn'])){
         header('Location: index.php');
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
        <form>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="jo_clientName">Client Name </label>
                    <input class="form-control" id="jo_clientName" name="jo_clientName" />
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_date">Date(mm/dd/yyyy) </label>
                    <input class="form-control" id="jo_date" name="jo_date" value="<?php echo date('m/d/Y');?>" readonly/>
                </div>
                <div class="form-group col-md-8">
                    <label for="jo_representative">Representative</label>
                    <input class="form-control" id="jo_representative" name="jo_representative" />
                </div>
                <div class="form-group col-md-4">
                    <label for="jo_tin">TIN#</label>
                    <input class="form-control" id="jo_tin" name="jo_tin" />
                </div>
                <div class="form-group col-md-12">
                    <label for="jo_address">Address</label>
                    <input class="form-control" id="jo_address" name="jo_tin" />
                </div>
                <div class="form-group col-md-12">
                    <label for="jo_location">Project Location</label>
                    <input class="form-control" id="jo_location" name="jo_tin" />
                </div>
            </div>
            <hr />
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="jo_description">Description</label>
                    <input class="form-control" id="jo_unit" name="jo_tin" />
                </div>
                <div class="form-group col-md-1 col-sm-6">
                    <label for="jo_unit">Unit</label>
                    <input class="form-control" id="jo_unit" name="jo_unit" />
                </div>
                <div class="form-group col-md-1 col-sm-6">
                    <label for="jo_quantity">Qty.</label>
                    <input class="form-control" id="jo_quantity" name="jo_quantity" />
                </div>
                <div class="form-group col-md-2">
                    <label for="jo_unitPrice">Unit Price</label>
                    <input class="form-control" id="jo_unitPrice" name="jo_unitPrice" />
                </div>
                <div class="form-group col-md-2">
                    <label for="jo_amount">Amount</label>
                    <input class="form-control" id="jo_amount" name="jo_amount" />
                </div>
                <div class="form-group col-md-1">
                    <label for="jo_add">&nbsp</label>
                    <button type="submit" class="form-control btn btn-primary" id="jo_add" name="jo_add">Add</button>
                </div>
            </div>
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Description</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                    <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <div class="form-row">
                <div class="form-group col-sm-12 col-md-4">
                    <label for="jo_creator">Created By:</label>
                    <input class="form-control" id="jo_creator" name="jo_creator" value="<?php echo $_SESSION['employee_fName']." ".$_SESSION['employee_mName']." ".$_SESSION['employee_lName']?>" readonly />
                </div>
                <div class="form-group col-sm-12 col-md-4">
                    <label for="jo_mobilization">Mobilization</label>
                    <input class="form-control" id="jo_mobilization" name="jo_mobilization" />
                </div>
                <div class="form-group col-sm-12 col-md-4">
                    <label for="jo_totalPayment">Total Payment</label>
                    <input class="form-control" id="jo_totalPayment" name="jo_totalPayment" readonly/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-primary" id="jo_save" name="jo_save">Save</button>
                </div>
                <div class="form-group col-md-3">
                    <a href="projects.php" type="button" class="form-control btn btn-danger" id="jo_cancel" name="jo_cancel">Cancel</a>
                </div>
            </div>
        </form>
        
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>