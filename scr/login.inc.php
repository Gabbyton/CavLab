<?php

  $email = $_POST['email'];
  $password = $_POST['pwd'];

  require 'dbh.inc.php';
  $dBTableName = "userslist";

  if ( empty( $email ) || empty($password) ) {
    header( "Location: ../login.php?error=emptyfields" );
    exit();
  }
  else {
    $sql="SELECT * FROM $dBTableName WHERE emailUsers=?";
    $stmt = mysqli_stmt_init($con);
    if ( !mysqli_stmt_prepare($stmt,$sql) ) {
      header("Location: ../login.php?error=mysqlerror");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt,"s",$email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ( $row = mysqli_fetch_assoc($result) ) {
        $pwdCheck = ( $password == $row['pwdUsers'] );
        if ( $pwdCheck == false ) {
          header("Location: ../login.php?error=wrongpwd");
          exit();
        }
        else {
          session_start();
          $_SESSION['userEmail'] = $row['emailUsers'];

          header("Location: ../index.php?login=success");
          exit();
        }
      }
      else {
        header("Location: ../login.php?error=nouser");
        exit();
      }
    }
  }

  mysqli_close($con);
 ?>
