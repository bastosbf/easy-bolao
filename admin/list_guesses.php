<?php
include '../config/connect.php';
session_start ();
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$player = $_POST ["player"];
$sql = "SELECT * FROM guess RIGHT JOIN game ON guess.id_game = game.id AND guess.id_player = $player ORDER BY game.date";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );

$sql = "SELECT * FROM team";
$team_result = mysql_query ( $sql );
$num_team_results = mysql_num_rows ( $team_result );
$teams = array ();
for($i = 1; $i <= $num_team_results; $i ++) {
	$row = mysql_fetch_array ( $team_result );
	$teams [$row ["id"]] = $row ["name"];
}

$sql = "SELECT * FROM player";
$player_result = mysql_query ( $sql );
$num_player_results = mysql_num_rows ( $player_result );
$players = array ();
for($i = 1; $i <= $num_player_results; $i ++) {
	$row = mysql_fetch_array ( $player_result );
	$players [$row ["id"]] = $row ["name"];
}

?>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body role="document">
  <div class="container theme-showcase" role="main">
    <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills pull-right">
          <li role="presentation">
            <a href="index.php">Início</a>
          </li>
          <li role="presentation">
            <a href="players.php">Jogadores</a>
          </li>
          <li role="presentation">
            <a href="teams.php">Times</a>
          </li>
          <li role="presentation">
            <a href="games.php">Jogos</a>
          </li>
          <li role="presentation" class="active">
            <a href="guesses.php">Palpites</a>
          </li>
          <li role="presentation">
            <a href="do_logout.php">Sair</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Administração</h3>
    </div>
    <div class="page-header">
      <h1>Palpites de <?=$players[$player]?></h1>
    </div>
    <form action="add_guesses.php" method="post">
      <input type="hidden" name="player" id="player" value="<?=$player?>" />
      <div class="row">
        <div class="col-md-12">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Time 1</th>
                <th>Placar 1</th>
                <th>Placar 2</th>
                <th>Time 2</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
            <?php
			for($i = 1; $i <= $num_results; $i ++) {
				$row = mysql_fetch_array ( $result );
			?>		
            <tr>
                <td><?=$i?></td>
                <td><?=$teams[$row["team_1"]]?></td>
                <td>
                  <input type="text" id="guess_1_<?=$row[6]?>" name="guess_1_<?=$row[6]?>" value="<?php if($row["guess_1"] != null) { echo $row["guess_1"]; }?>" size="2" />
                </td>
                <td>
                  <input type="text" id="guess_2_<?=$row[6]?>" name="guess_2_<?=$row[6]?>" value="<?php if($row["guess_2"] != null) { echo $row["guess_2"]; }?>" size="2" />
                </td>
                <td><?=$teams[$row["team_2"]]?></td>
                <td><?=$row["date"]?></td>
              </tr>
            <?php
			}
			?>
          </tbody>
          </table>
          <p align="right">
            <input type="submit" class="btn btn-lg btn-primary" value="Salvar Palpites" />
          </p>
        </div>
      </div>
    </form>
  </div>
</body>
</html>