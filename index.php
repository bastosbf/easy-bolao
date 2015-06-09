<?php
include 'config/connect.php';

$sql = "SELECT * FROM player";
$player_result = mysql_query ( $sql );
$num_player_results = mysql_num_rows ( $player_result );
$players = array ();
for($i = 1; $i <= $num_player_results; $i ++) {
	$row = mysql_fetch_array ( $player_result );
	$players [$row ["id"]] = $row ["name"];
}

$sql = "SELECT * FROM player INNER JOIN guess ON player.id = guess.id_player INNER JOIN game ON guess.id_game = game.id WHERE game.score_1 <> -1 AND game.score_2 <> -1 GROUP BY player.name";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$scores = array ();
$hits = array ();
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$guess_1 = $row ["guess_1"];
	$guess_2 = $row ["guess_2"];
	$score_1 = $row ["score_1"];
	$score_2 = $row ["score_2"];
	
	$score = 0;
	
	$guess_draw = $guess_1 == $guess_2;
	$score_draw = $score_1 == $score_2;
	
	if ($guess_1 == $score_1 && $guess_2 == $score_2) {
		$score += 4;
		if ($hits [$row ["name"]] == null) {
			$hits [$row ["name"]] = 0;
		}
		$hits [$row ["name"]] += 1;
	} else if ($guess_draw && $score_draw) {
		$score += 1;
	} else {
		if ($guess_1 == $score_1 || $guess_2 == $score_2) {
			$score += 1;
		}
		$guess_win_team_1 = $guess_1 > $guess_2;
		$score_win_team_1 = $score_1 > $score_2;
		if ($guess_win_team_1 == $score_win_team_1) {
			$score += 1;
		}
	}
	if ($scores [$row ["name"]] == null) {
		$scores [$row ["name"]] = 0;
	}
	$scores [$row ["name"]] += $score;
}
arsort ( $score );
arsort ( $hit );

array_multisort ( $score, SORT_DESC, $hit, SORT_DESC );

$sql = "SELECT * FROM team";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$teams = array ();
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$teams [$row ["id"]] = $row ["name"];
}

date_default_timezone_set ( "America/Sao_Paulo" );
$current_date = date ( "d/m/Y" );
$sql = "SELECT * FROM game WHERE date LIKE '%$current_date%' ORDER BY date";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$games = array ();
$guesses = array ();
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$team_1 = $row ["team_1"];
	$team_2 = $row ["team_2"];
	$score_1 = $row ["score_1"];
	$score_2 = $row ["score_2"];
	
	$games ["$teams[$team_1] s1 x s2 $teams[$team_2]"] = array (
			$score_1,
			$score_2 
	);
	
	$sql = "SELECT * FROM guess INNER JOIN player ON guess.id_player = player.id WHERE id_game = " . $row ["id"] . " ORDER BY player.name";
	$guess_result = mysql_query ( $sql );
	$guess_num_results = mysql_num_rows ( $guess_result );
	for($j = 1; $j <= $guess_num_results; $j ++) {
		$guess_row = mysql_fetch_array ( $guess_result );
	}
	$guesses ["$teams[$team_1] s1 x s2 $teams[$team_2]"] = array ();
	$guesses ["$teams[$team_1] s1 x s2 $teams[$team_2]"] [$players [$guess_row ["id_player"]]] = array (
			$guess_row ["guess_1"],
			$guess_row ["guess_2"] 
	);
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
    <div class="page-header">
      <h1>Classificação</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>Pontos</th>
            </tr>
          </thead>
          <tbody>
	        <?php
			$i = 1;
			foreach ( $scores as $k => $v ) {
			?>
          	<tr>
              <td><?=$i?></td>
              <td><?=$k?></td>
              <td><?=$v?></td>
            </tr>
	        <?php
				$i ++;
			}
			?>          
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <h3>Por pontos</h3>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>Pontos</th>
            </tr>
          </thead>
          <tbody>
	        <?php
			$i = 1;
			foreach ( $scores as $k => $v ) {
			?>
          	<tr>
              <td><?=$i?></td>
              <td><?=$k?></td>
              <td><?=$v?></td>
            </tr>
	        <?php
				$i ++;
			}
			?>          
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
        <h3>Por palpites corretos</h3>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>Pontos</th>
            </tr>
          </thead>
          <tbody>
	        <?php
			$i = 1;
			foreach ( $hits as $k => $v ) {
			?>
          	<tr>
              <td><?=$i?></td>
              <td><?=$k?></td>
              <td><?=$v?></td>
            </tr>
	        <?php
				$i ++;
			}
			?>          
          </tbody>
        </table>
      </div>
    </div>
    <div class="page-header">
      <h1>Próximos Jogos</h1>
    </div>
    <?php
	foreach ( $games as $k => $v ) {
		$s1 = $v [0];
		$s2 = $v [1];
		if ($s1 == - 1) {
			$s1 = "";
		}
		if ($s2 == - 1) {
			$s2 = "";
		}
		$game = $k;
		$game = str_replace ( "s1", $s1, $game );
		$game = str_replace ( "s2", $s2, $game );
	?>
    <h3><?=$game?></h3>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>Placar</th>
            </tr>
          </thead>
          <tbody>
           <?php
			$i = 1;
			foreach ( $guesses [$k] as $guess_k => $guess_v ) {
			?>
            <tr>
              <td><?=$i?></td>
              <td><?=$guess_k?></td>
              <td><?php echo $guess_v[0] . " x " . $guess_v[1];?></td>
            </tr>
            <?php
			}
			?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
	}
	?>
  </div>
</body>
</html>
