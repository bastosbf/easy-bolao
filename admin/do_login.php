<?php
include '../config/connect.php';

$username = $_POST ["username"];
$password = md5 ( $_POST ["password"] );

$sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
if ($num_results == 0) {
	Header ( "Location:login.php?error=1" );
}
$row = mysql_fetch_array ( $result );
$_SESSION ["logged"] = $row ["username"];
Header ( "Location:index.php" );
?>