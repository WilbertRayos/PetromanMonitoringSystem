<?php
    if(!isset($_SESSION)){
      session_start();
    }

    if(isset($_POST['logout'])){
      session_destroy();
      header('Location: index.php');
    }else if(isset($_POST['updateInfo'])){
      if(strcmp($_POST['employee_fName'], $_SESSION['employee_fName']) == 0 
        && strcmp($_POST['employee_mName'], $_SESSION['employee_mName']) == 0 
        && strcmp($_POST['employee_lName'], $_SESSION['employee_lName']) == 0
        && strcmp($_POST['employee_email'], $_SESSION['employee_email']) == 0
        && strcmp($_POST['employee_password'], $_SESSION['employee_password']) == 0){
          echo "Nothing to update";
        $_POST = array();
      }else{
        require_once('db_ops.php');
        $db_obj = new Update_User_Information;
        $db_obj->setID($_SESSION['employee_id']);
        $db_obj->setFirstName($_POST['employee_fName']);
        $db_obj->setMiddleName($_POST['employee_mName']);
        $db_obj->setLastName($_POST['employee_lName']);
        $db_obj->setEmail($_POST['employee_email']);
        $db_obj->setPassword($_POST['employee_password']);
        $db_obj->updateUserInformation();
      }
    }
 
    $path_parts = pathinfo($_SERVER['REQUEST_URI']);

?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#" data-toggle="modal" data-target="#exampleModal">
        <?php
            if($_SESSION['employee_role'] == 'Admin'){
                echo "Admin ";
            }else{
                echo "Agent ";
            }
            echo $_SESSION['employee_fName']." ".$_SESSION['employee_lName'];
        ?>
    </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item 
        <?php 
            if($path_parts['filename'] == 'projects'){
                echo "active";
            }
        ?>">
        <a class="nav-link" href="projects.php">Projects<span class="sr-only"></span></a>
      </li>
      <li class="nav-item 
        <?php 
            if($path_parts['filename'] == 'trading_sales'){
                echo "active";
            }
        ?>">
        <a class="nav-link" href="trading_sales.php">Trading Sales<span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <?php
            if($_SESSION['employee_role'] == 'Admin'){
                echo "<a class='nav-link' href='accounts.php'>Accounts</a>";
            }
        ?>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="financialReport" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Financial Report
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="finance_job_order.php">Job Order</a>
          <a class="dropdown-item" href="finance_trading_sales.php">Trading Sales</a>

        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" action="#" method="POST">
      <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" name="logout" value="logout">Logout</button>
    </form>
  </div>

  <!-- User Loggin Info Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="<?php echo $path_parts['basename'];?>" method="POST" id="userInfo">
              <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="input" class="form-control" id="employee_id" value="<?php echo $_SESSION['employee_id']; ?>" readonly>
                <label for="employee_role">Role</label>
                <input type="input" class="form-control" id="employee_role" value="<?php echo $_SESSION['employee_role']; ?>" readonly>
                <label for="employee_fName">First Name</label>
                <input type="input" class="form-control" id="employee_fName" name="employee_fName" value="<?php echo $_SESSION['employee_fName'] ?>">
                <label for="employee_mName">First Name</label>
                <input type="input" class="form-control" id="employee_mName" name="employee_mName" value="<?php echo $_SESSION['employee_mName'] ?>">
                <label for="employee_lName">First Name</label>
                <input type="input" class="form-control" id="employee_lName" name="employee_lName" value="<?php echo $_SESSION['employee_lName'] ?>">
                <label for="employee_email">First Name</label>
                <input type="email" class="form-control" id="employee_email" name="employee_email" value="<?php echo $_SESSION['employee_email'] ?>">
                <label for="employee_password">Password</label>
                <input class="form-control" type="password" id="employee_password" name="employee_password" value="<?php echo $_SESSION['employee_password'] ?>">
                <input type="checkbox" onclick="myFunction('employee_password')"> Show Password
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="userInfo" value="userInfo" name="updateInfo">Update Information</button>
        </div>
      </div>
    </div>
  </div>
  
</nav>

