<?php
include '../config/connect.php';

$name = $_POST["name"];
$sql = "INSERT INTO player (name) VALUES ('$name')";
mysql_query($sql) or die(mysql_error());;
Header("Location:players.php?success=1");
?>