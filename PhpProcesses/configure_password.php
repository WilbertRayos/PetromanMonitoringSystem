<?php
    require_once '../db_ops.php';

    $obj_fetching = new Fetch_Particular_Email($_GET["code"]);
    $email = $obj_fetching->fetch_email();
 
    if(!isset($email) || empty($email)) {
        exit("Can't find page");
    }
    $code = $_GET["code"];
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";;

    if(isset($_POST["password_submit"])) {
        $password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_new_password"];
        if (!isset($password) || empty($password)) {
            echo "<script>alert('Please indicate your new password');</script>";
        }else if (!isset($confirm_password) || empty($confirm_password)) {
            echo "<script>alert('Please confirm your new password');</script>";
        }else if (strcmp($password, $confirm_password) != 0) {
            echo "<script>alert('New password and confirm new password are not the same');</script>";
        }else {
            $obj_update_password = new Update_Employee_Password($password);
            $verdict = $obj_update_password->update_password();
            echo $verdict; 
            if($verdict == 0) {
                $obj_update_password->delete_code($code,$email);
                echo "<script>alert('Password has been modified');</script>";
            }else {
                echo "<script>alert('Error occured. Password was not modified');</script>";
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

    <title>Hello, world!</title>
  </head>
  <body>
    
    <div class="container">
        <form action="<?php echo $url;?>" method="POST" id="reset_password_form">
            <div class="form-group">
                <label for="New Password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
            </div>
            <div class="form-group">
                <label for="New Password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="password_submit" value="password_submit" form="reset_password_form">Submit</button>
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