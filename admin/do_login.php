<?php
$user = $_POST ["user"];
$password = md5 ( $_POST ["password"] );

$sql = "SELECT * FROM admin WHERE user = '$user' AND password = '$password'";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
if ($num_results == 0) {
	Header("Location:admin/login.php?error=1");
}
Header("Location:admin/index.php");
?>