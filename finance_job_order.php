<?php
session_start();
if (!isset($_SESSION['loggedIn']) ) {
    header('Location: index.php');
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

    <title>Hello, world!</title>
  </head>
  <body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h3 class="display-4">Job Orders</h3>
        <div class="row md-mt-3 justify-content-end">
            <div class="col-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Job Order #" aria-label="Job Order #" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                <th scope="col">JO Order #</th>
                <th scope="col">Remaining Balance</th>
                <th scope="col">Aging</th>
                <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td><a href="#" onclick="clickme(this)">bruhh</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                </tr>


            </tbody>
            </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
  <script>
      function clickme(obj){
        var joNum = $(obj).text();
        alert(joNum);
      }
  </script>
</html>