<?php
$server = "localhost:3306";
$db = "bolao";
$user = "root";
$password = "123456";

$conn = @mysql_connect ( $server, $user, $password );
@mysql_select_db ( $db, $conn ) or die ( "Error while connecting to the database!" );
?>