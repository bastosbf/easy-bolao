<?php
include '../config/connect.php';
include '../config/config.php';
if ($_SESSION ["logged"] != 0 && $_SESSION ["logged"] != 1) {
	Header ( "Location:login.php" );
}

$game = $_POST ["game"];
$score_1 = $_POST ["score_1"];
$score_2 = $_POST ["score_2"];

$sql = "UPDATE game SET score_1 = $score_1, score_2 = $score_2 WHERE id = $game";
mysql_query ( $sql ) or die ( mysql_error () );
Header ( "Location:games.php?edited=1" );
?>