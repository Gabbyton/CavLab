<?php
  $showNoUser = "Use your ISAK Account to Log in";
  $showRed = "";

  if( isset($_GET['error']) ) {
    if ($_GET['error'] == "wrongpwd") {
      $showRed = "Incorrect Password";
    }

  if ($_GET['error'] == "nouser") {
    $showNoUser = "No such user";
  }
}
 ?>
 <!doctype HTML>
<html>

<title>CavLab | Log in</title>

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
  <header class="header bg-primary p-4 text-light">
    <div class="container" id="titleBar">
      <img src="img\logo.png" class="img-fluid mr-2" alt="Atom.png">CavLab
    </div>
  </header>

  <main role="main">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-1 col-xs-12 border border-info p-5 mt-5" style="margin: 0 auto;">
        <h4 class="text-center">Log in</h4>
        <form action="scr/login.inc.php" method="post">
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" class="form-control" id="emailInput" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted"><?php echo $showNoUser; ?></small>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="pwd" class="form-control" id="pwdInput" placeholder="Password">
            <div id="warningBox"><small id="emailHelp" class="form-text text-danger"><?php echo $showRed; ?></small></div>
          </div>
          <button type="submit" class="btn btn-primary float-right" action="post" >Submit</button>
        </form>
      </div>
  </div>
</main>
  <footer class="footer p-2 text-center mt-5">
    Copyright &copy;2018 UWC ISAK Japan All Rights Reserved.
  </footer>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script type="text/javascript">

</script>

</html>
