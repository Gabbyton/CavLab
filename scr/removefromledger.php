<?php
session_start();

$email = $_SESSION['userEmail'];
$code = intval( $_POST['code'] );

require 'dbh.inc.php';

// sql to delete a record
$sql = "DELETE FROM $dBTableName WHERE ( itemCodeLedger=? AND emailLedger='".$email."')";
$stmt = mysqli_stmt_init($con);
if ( !mysqli_stmt_prepare($stmt,$sql) ) {
  header("Location: login.php?error=mysqlerror");
  exit();
}
else {
  mysqli_stmt_bind_param($stmt,"i",$code);
  if (mysqli_stmt_execute($stmt)) {
      echo "success_delete_record_ledger";
  } else {
      echo "error_deleting_record";
      // echo "Error deleting record: " . $conn->error;
  }
}

mysqli_close($con);
?>
