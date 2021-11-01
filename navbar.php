<?php
    
    if(isset($_POST['logout'])){
        session_destroy();
        header('Location: index.php');
    }
 
    $path_parts = pathinfo($_SERVER['REQUEST_URI']);
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
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
            if($path_parts['filename'] == 'dashboard'){
                echo "active";
            }
        ?>">
        <a class="nav-link" href="#">Dashboard<span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" action="#" method="POST">
      <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" name="logout" value="logout">Logout</button>
    </form>
  </div>
</nav>