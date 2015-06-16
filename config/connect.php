<?php
$server = "localhost:3306";
$db = "bolao";
$user = "root";
$password = "";

$conn = @mysql_connect ( $server, $user, $password );
@mysql_select_db ( $db, $conn ) or die ( "Error while connecting to the database!" );
?>