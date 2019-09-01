<?php
session_start();

$useremail = $_SESSION['userEmail'];

require 'dbh.inc.php';

mysqli_select_db($con,$dBname);
$sql="SELECT * FROM $dBTableName WHERE emailLedger = '".$useremail."'";
$result = mysqli_query($con,$sql);

if ( mysqli_num_rows($result) > 0 ) {
  while($row = mysqli_fetch_array($result)) {
      echo $row['itemNameLedger'] . ';';
      echo $row['itemCodeLedger'] . ';';
      echo $row['borrowDate'] . '%';
  }
}
else {
  echo "error_no_items_assoc";
}

mysqli_close($con);
?>
