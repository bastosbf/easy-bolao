<?php
include '../config/connect.php';

$name = $_POST["name"];
$sql = "INSERT INTO team (name) VALUES ('$name')";
mysql_query($sql) or die(mysql_error());;
Header("Location:teams.php?success=1");
?>