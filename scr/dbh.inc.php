<?php

$servername = "localhost";
$dBusername = "root";
$dBpword = "futur3_4Ward";
$dBname = "physics_lab_app";
$dBTableName = "appledger";

$con = mysqli_connect($servername, $dBusername, $dBpword, $dBname);
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

 ?>
