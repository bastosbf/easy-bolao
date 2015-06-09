<?php
include '../config/connect.php';
session_start ();
if ($_SESSION ["logged"] == null) {
	Header ( "Location:login.php" );
}

$name = $_POST ["name"];
$sql = "INSERT INTO team (name) VALUES ('$name')";
mysql_query ( $sql ) or die ( mysql_error () );
Header ( "Location:teams.php?added=1" );
?>