<?php
include '../config/connect.php';
include '../config/config.php';
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$name = $_POST ["name"];
$sql = "INSERT INTO team (name) VALUES ('$name')";
mysql_query ( $sql ) or die ( mysql_error () );
Header ( "Location:teams.php?added=1" );
?>