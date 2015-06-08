<?php
include 'config/connect.php';

$sql = "SELECT * FROM player INNER JOIN guess ON player.id = guess.id_player INNER JOIN game ON guess.id_game = game.id WHERE game.score_1 <> -1 AND game.score_2 <> -1 GROUP BY player.name";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$scores = array ();
$hits = array();
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
		if($hits[$row["name"]] == null) {
			$hits[$row["name"]] = 0;
		}
		$hits[$row["name"]] += 1;
	} else if ($guess_draw && $score_draw) {
		$score += 1;
	} else {
		if ($guess_1 == $score_1 || $guess_2 == $score_2) {
			$score += 1;
		}
		$guess_win_team_1 = $guess_1 > $guess_2;
		$score_win_team_1 = $score_1 > $score_2;
		if($guess_win_team_1 == $score_win_team_1) {
			$score += 1;
		}
	}
	if($scores[$row["name"]] == null) {
		$scores[$row["name"]] = 0;
	}
	$scores[$row["name"]] += $score;
}
arsort($score);
arsort($hit);

$current_date = date("d/m/Y");

$sql = "SELECT * FROM game INNER JOIN guess ON game.id = guess.id_game INNER JOIN player ON player.id = guess.id_player WHERE game.date LIKE '%$current%' ";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$games = array();
$guesses = array();
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$team_1 = $row["team_1"];
	$team_2 = $row["team_2"];
	$score_1 = $row["score_1"];
	$score_2 = $row["score_2"];
	
	$games["$team_1 x $team_2"];
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
	        foreach ($scores as $k => $v) {
	        ?>
          	<tr>
              <td><?=$i?></td>
              <td><?=$k?></td>
              <td><?=$v?></td>
            </tr>
	        <?php
	        $i++;	
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
	        foreach ($scores as $k => $v) {
	        ?>
          	<tr>
              <td><?=$i?></td>
              <td><?=$k?></td>
              <td><?=$v?></td>
            </tr>
	        <?php
	        $i++;	
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
	        foreach ($hits as $k => $v) {
	        ?>
          	<tr>
              <td><?=$i?></td>
              <td><?=$k?></td>
              <td><?=$v?></td>
            </tr>
	        <?php
	        $i++;	
	        }
	        ?>          
          </tbody>
        </table>
      </div>
    </div>
    <div class="page-header">
      <h1>Próximos Jogos</h1>
    </div>
    <h3>Brasil 2 x 1 Argentina</h3>
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
            <tr>
              <td>1</td>
              <td>Mark</td>
              <td>Otto</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Jacob</td>
              <td>Thornton</td>
            </tr>
            <tr>
              <td>3</td>
              <td>Larry</td>
              <td>the Bird</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
