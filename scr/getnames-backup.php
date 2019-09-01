<?php
$itemCode = intval($_GET['q']);

require 'dbh.inc.php';
$dBTableName = "inventory";

mysqli_select_db( $con , $dBname );
$sql="SELECT * FROM $dBTableName WHERE itemCode = '".$itemCode."'";
$result = mysqli_query($con,$sql);

if ( mysqli_num_rows($result) > 0 ) {
  while($row = mysqli_fetch_array($result)) {
      echo $row['itemName'];
  }
}
else {
  echo "error_no_such_item";
}

mysqli_close($con);
?>
