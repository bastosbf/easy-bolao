<?php
include '../config/connect.php';
session_start();

$username = $_POST ["username"];
$password = md5 ( $_POST ["password"] );

$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
if ($num_results == 0) {
	Header ( "Location:login.php?error=1" );
}
$row = mysql_fetch_array ( $result );
$_SESSION ["logged"] = $row ["role"];
Header ( "Location:index.php" );
?>