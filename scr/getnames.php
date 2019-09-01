<?php
$itemCode = intval($_GET['q']);

require 'dbh.inc.php';
$dBTableName = "inventory";

$sql="SELECT * FROM $dBTableName WHERE itemCode=?";
$stmt = mysqli_stmt_init($con);
if ( !mysqli_stmt_prepare($stmt,$sql) ) {
  header("Location: login.php?error=mysqlerror");
  exit();
}
else {
  mysqli_stmt_bind_param($stmt,"i",$itemCode);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if ( mysqli_num_rows($result) > 0 ) {
    while($row = mysqli_fetch_array($result)) {
        echo $row['itemName'];
    }
  }
  else {
    echo "error_no_such_item";
    exit();
  }
}

mysqli_close($con);
?>
