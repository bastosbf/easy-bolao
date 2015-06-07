<?php
include '../config/connect.php';

$player = $_POST ["player"];
$sql = "SELECT MAX(id) FROM game";
$result = mysql_query ( $sql );
$row = mysql_fetch_array ( $result );
for($i = 1; $i <= $row [0]; $i ++) {
	if(isset($_POST["guess_1_$i"]) && isset($_POST["guess_2_$i"])) {
		$sql = "SELECT id FROM guess WHERE id_player = $player AND id_game = $i";
		$result = mysql_query ( $sql );
		$num_results = mysql_num_rows ( $result );
		if($num_results == 1) {
			$sql = "UPDATE guess SET guess_1 = ".$_POST["guess_1_$i"].", guess_2 = ".$_POST["guess_2_$i"]." WHERE id_player = $player AND id_game = $i";
			mysql_query($sql) or die(mysql_error());;
			Header("Location:guesses.php?edited=1");
		} else {
			$sql = "INSERT INTO guess (id_player, id_game, guess_1, guess_2) VALUES ($player, $i,".$_POST["guess_1_$i"].",".$_POST["guess_2_$i"].")";
			mysql_query($sql) or die(mysql_error());;
			Header("Location:guesses.php?added=1");
		}
	}	
}
?>