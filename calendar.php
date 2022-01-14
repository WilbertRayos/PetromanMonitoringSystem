<?php
session_start();
require_once('db_ops.php');
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
}
$obj_itinerary_calendar = new Itinerary_Calendar;

if (isset($_POST['memo_save'])) {
    if (!isset($_POST['date_picker']) || empty($_POST['date_picker'])){
        echo "<script>alert('Please pick a date');</script>";
    } else if (!isset($_POST['memo_title']) || empty($_POST['memo_title'])) {
        echo "<script>alert('Please fill-up title');</script>";
    } else if (!isset($_POST['memo_message']) || empty($_POST['memo_message'])) {
        echo "<script>alert('Please fill-up message');</script>";
    } else {
        
        $obj_itinerary_calendar->addMemo($_SESSION['employee_id'], $_POST['date_picker'], $_POST['memo_title'], $_POST['memo_message']);
    }  
}

if (isset($_POST["memo_delete"])) {
    if (!isset($_POST["end_memo_id"]) || empty($_POST["end_memo_id"])) {
        echo "<script>alert('Please enter the id number of the memo you want to delete');</script>";
    } else if($_POST["end_memo_id"] < 1) {
        echo "<script>alert('Invalid Memo Id Number');</script>";
    } else {
        $memo_exists = $obj_itinerary_calendar->memoIdExists($_POST["end_memo_id"]);
        if ($memo_exists) {
            $delete_verdict = $obj_itinerary_calendar->deleteMemo($_POST["end_memo_id"], $_SESSION['employee_id'], $_SESSION['employee_role']);
            if ($delete_verdict == -1) {
                echo "<script>alert('You are not eligible to delete this particular note');</script>";
            } else if ($delete_verdict == 1) {
                echo "<script>alert('Note has been deleted');</script>";
            }
        } else {
            echo "<script>alert('No such memo id exists');</script>";
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
    <title>Calendar</title>
    <style>
        
    </style>
  </head>
  <body>
   
    <?php require('navbar.php');?>

    <div class="container">
        <h1 class="display-4 my-4 page-title">Itinerary Calendar</h1>
        <div class="row">
            <div class="col-md-4 menu-box">
                <h3 class="h3 mt-3 menu-box-title">Add Memo</h3>
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="date_setter">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="date_picker">Pick Date</label>
                            <input class="form-control" type="date" id="date_picker" name="date_picker">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="memo_title">Title</label>
                            <input class="form-control" type="text" id="memo_title" name="memo_title">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="memo_message">Message</label>
                            <textarea class="form-control" id="memo_message" name="memo_message" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <button type="submit" class="form-control btn btn-success" form="date_setter" id="memo_save" name="memo_save">Add</button>
                        </div>
                    </div>
                </form>
                <hr />
                <h3 class="h3 menu-box-title">Delete Memo</h3>
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="memo_ender">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="memo_id">Memo #</label>
                            <input class="form-control" type="number" id="end_memo_id" name="end_memo_id" min="1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <button type="submit" class="form-control btn btn-danger mb-3" form="memo_ender" id="memo_delete" name="memo_delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <?php 
                    $all_memos = $obj_itinerary_calendar->fetchMemos();
                    foreach($all_memos as $memo) {
                ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col col-md-10">
                                    <?php echo date_format(new DateTime($memo['memo_date']), "F j, Y, l"); ?>
                                </div>
                                <div class="col col-md-2 text-right">
                                    <?php echo 'Memo #:'.$memo['memo_id']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                            <h3 class="h3"><?php echo $memo['memo_title']; ?></h3>
                            <p><?php echo $memo['memo_message']; ?></p>
                            <footer class="blockquote-footer">Created by  <cite title="Source Title"><?php echo $memo['employee_name']; ?></cite></footer>
                            </blockquote>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  </body>
</html>