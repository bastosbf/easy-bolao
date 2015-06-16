<?php
include '../config/connect.php';
include '../config/config.php';
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$position = $_POST ["position"];
$team = $_POST ["team"];
$sql = "INSERT INTO finalist (position, id_team) VALUES ($position, $team)";
if(mysql_query ( $sql )) {
	Header ( "Location:finalists.php?added=1" );
} else {
	Header ( "Location:finalists.php?error=1" );
}
?>