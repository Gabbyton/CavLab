<?php
  session_start();
  session_unset();
  session_destroy();
 ?>

 <title>Logged Out | Physics Lab App</title>

 <head>

   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

 </head>

 <header class="header bg-primary p-4 text-light">
   <div class="container" id="titleBar">
     <img src="img\logo.png" class="img-fluid mr-2" alt="Atom.png">CavLab
   </div>
 </header>

 <main role="main">
   <div class="row">
     <div class="col-lg-4 col-lg-offset-1 col-xs-12 text-center">
       <h1 class="text-danger display-5 mt-5">You're currently logged out</h1>
       <p>
         Log in to continue with the site
       </p>
       <a href="login.php"><button type="button" class="btn btn-outline-secondary mt-1">Log in</button></a>
     </div>
 </div>
</main>
 <footer class="footer p-2 text-center mt-5">
   Copyright (c) 2018 Copyright Holder All Rights Reserved.
 </footer>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>
