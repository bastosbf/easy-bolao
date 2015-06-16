<?php
include '../config/connect.php';
include '../config/config.php';
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$team_1 = $_POST ["team_1"];
$team_2 = $_POST ["team_2"];
if ($team_1 == $team_2) {
	Header ( "Location:games.php?error=1" );
}
$date = $_POST ["date"];
$sql = "INSERT INTO game (team_1, team_2, date) VALUES ('$team_1', '$team_2', '$date')";
mysql_query ( $sql ) or die ( mysql_error () );
Header ( "Location:games.php?added=1" );
?>