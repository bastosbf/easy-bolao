<?php
include '../config/connect.php';
include '../config/config.php';
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$player = $_POST ["player"];
$position = $_POST ["position"];
$team = $_POST ["team"];
$sql = "INSERT INTO finalist_guess (id_player, position, id_team) VALUES ($player, $position, $team)";
if(mysql_query ( $sql )) {
	Header ( "Location:list_guesses.php?player=$player&added=1" );
} else {
	Header ( "Location:list_guesses.php?player=$player&error=1" );
}
?>