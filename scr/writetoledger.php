<?php
session_start();

$email = $_SESSION['userEmail'];
$itemName = $_POST['itemName'];
$code = intval( $_POST['code'] );
$date = $_POST['date'];

require 'dbh.inc.php';

$sql = "INSERT INTO $dBTableName (emailLedger, itemNameLedger, itemCodeLedger, borrowDate)
VALUES (?,?,?,?)";
$stmt = mysqli_stmt_init($con);
if ( !mysqli_stmt_prepare($stmt,$sql) ) {
  header("Location: login.php?error=mysqlerror");
  exit();
}
else {
  mysqli_stmt_bind_param($stmt,"ssis",$email,$itemName,$code,$date);
  if ( mysqli_stmt_execute($stmt) ) {
      echo "success_write_database";
  } else {
      echo "error_cannot_write_to_database";
       // echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

mysqli_close($con);
?>
