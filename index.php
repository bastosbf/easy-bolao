<?php
include 'config/connect.php';
error_reporting(E_ERROR);

$sql = "SELECT * FROM player";
$player_result = mysql_query ( $sql );
$num_player_results = mysql_num_rows ( $player_result );
$players = array ();
for($i = 1; $i <= $num_player_results; $i ++) {
	$row = mysql_fetch_array ( $player_result );
	$players [$row ["id"]] = $row ["name"];
}

$sql = "SELECT * FROM player INNER JOIN guess ON player.id = guess.id_player INNER JOIN game ON guess.id_game = game.id WHERE game.score_1 <> -1 AND game.score_2 <> -1 ORDER BY player.name";
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
	if ($hits [$row ["name"]] == null) {
		$hits [$row ["name"]] = 0;
	}
	if ($scores [$row ["name"]] == null) {
		$scores [$row ["name"]] = 0;
	}
	if ($guess_1 == $score_1 && $guess_2 == $score_2) {
		$score += 4;		
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
	$scores [$row ["name"]] += $score;
}

$i = 0;
$data = array();
foreach($scores as $k => $v) {
	$data[$i] = array($k, $v, $hits[$k]);
	$i++;
}
array_multisort ( $scores, SORT_DESC, $hits, SORT_DESC, $data);

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
$next_games = array ();
$next_game_guesses = array ();
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$team_1 = $row ["team_1"];
	$team_2 = $row ["team_2"];
	$score_1 = $row ["score_1"];
	$score_2 = $row ["score_2"];
	$date = $row["date"];
	
	$next_games ["$teams[$team_1] s1 x s2 $teams[$team_2] - $date"] = array (
			$score_1,
			$score_2 
	);
	$next_game_guesses ["$teams[$team_1] s1 x s2 $teams[$team_2] - $date"] = array ();
	
	$sql = "SELECT * FROM guess INNER JOIN player ON guess.id_player = player.id WHERE id_game = " . $row ["id"] . " ORDER BY player.name";
	$guess_result = mysql_query ( $sql );
	$guess_num_results = mysql_num_rows ( $guess_result );
	for($j = 1; $j <= $guess_num_results; $j ++) {
		$guess_row = mysql_fetch_array ( $guess_result );
		$next_game_guesses ["$teams[$team_1] s1 x s2 $teams[$team_2] - $date"] [$players [$guess_row ["id_player"]]] = array (
				$guess_row ["guess_1"],
				$guess_row ["guess_2"] 
		);
	}
}

$previous_date = date('d/m/Y', strtotime(' -1 day'));
$sql = "SELECT * FROM game WHERE date LIKE '%$previous_date%' ORDER BY date";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$previous_games = array ();
$previous_game_guesses = array ();
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$team_1 = $row ["team_1"];
	$team_2 = $row ["team_2"];
	$score_1 = $row ["score_1"];
	$score_2 = $row ["score_2"];
	$date = $row["date"];

	$previous_games ["$teams[$team_1] s1 x s2 $teams[$team_2] - $date"] = array (
			$score_1,
			$score_2
	);
	$previous_game_guesses ["$teams[$team_1] s1 x s2 $teams[$team_2] - $date"] = array ();

	$sql = "SELECT * FROM guess INNER JOIN player ON guess.id_player = player.id WHERE id_game = " . $row ["id"] . " ORDER BY player.name";
	$guess_result = mysql_query ( $sql );
	$guess_num_results = mysql_num_rows ( $guess_result );
	for($j = 1; $j <= $guess_num_results; $j ++) {
		$guess_row = mysql_fetch_array ( $guess_result );
		$previous_game_guesses ["$teams[$team_1] s1 x s2 $teams[$team_2] - $date"] [$players [$guess_row ["id_player"]]] = array (
				$guess_row ["guess_1"],
				$guess_row ["guess_2"]
		);
	}
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
			$canwrite = true;
			$old = null;
			foreach ( $scores as $k => $v ) {
				$canwrite = $old != $v;
				$old = $v;
			?>
          	<tr>
              <td><?if($canwrite){ echo $i; }?></td>
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
			$canwrite = true;
			$old = null;
			foreach ( $scores as $k => $v ) {
				$canwrite = $old != $v;
				$old = $v;
			?>
          	<tr>
              <td><?if($canwrite){ echo $i; }?></td>
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
              <th>Palpites</th>
            </tr>
          </thead>
          <tbody>
	        <?php
			$i = 1;
			$canwrite = true;
			$old = null;
			foreach ( $hits as $k => $v ) {
				$canwrite = $old != $v;
				$old = $v;
			?>
          	<tr>
              <td><?if($canwrite){ echo $i; }?></td>
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
	foreach ( $next_games as $k => $v ) {
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
			foreach ( $next_game_guesses [$k] as $guess_k => $guess_v ) {			
			?>
            <tr <?php if($guess_v[0] == $s1 && $guess_v[1] == $s2) {echo 'class="success"';}?>>
              <td><?=$i?></td>
              <td><?=$guess_k?></td>
              <td><?php echo $guess_v[0] . " x " . $guess_v[1];?></td>
            </tr>
            <?php
            $i++;
			}
			?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
	}
	?>
	 <div class="page-header">
      <h1>Jogos Anteriores</h1>
    </div>
    <?php
	foreach ( $previous_games as $k => $v ) {
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
			foreach ( $previous_game_guesses [$k] as $guess_k => $guess_v ) {			
			?>
            <tr <?php if($guess_v[0] == $s1 && $guess_v[1] == $s2) {echo 'class="success"';}?>>
              <td><?=$i?></td>
              <td><?=$guess_k?></td>
              <td><?php echo $guess_v[0] . " x " . $guess_v[1];?></td>
            </tr>
            <?php
            $i++;
			}
			?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
	}
	?>
	<footer>
      <p class="pull-right">
        <a href="#">Topo</a>
      </p>
      <p>
        ©
        <a href="https://github.com/bastosbf/easy-bolao" target="_blank">Easy Bolão</a>
        . ·
        <a href="#" onclick="showRulesModal();">Regras</a>
      </p>
    </footer>
  </div>
  <div id="rules-modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2>Easy Bolão!</h2>
          <p class="lead">Easy Bolão foi criado para gerênciar palpites de jogos de futebol entre vários participantes.</p>
          <h2>Regras</h2>
          <p class="lead">1) Acerto exato do placar da partida = 4 pontos;</p>
          <p class="lead">2) Acerto do resultado, com placar diferente = 1 ponto (Ex. O placar da partida foi Seleção "A" 1 x 0 Seleção "B". O apostador arriscou que seria Seleção "A" 2 x 1 Seleção
            "B"). O mesmo critério vale para uma partida que termine empatada;</p>
          <p class="lead">3) Acerto do número de gols de uma seleção, com placar diferente = 1 ponto (Ex. O placar da partida foi Seleção "A" 1 x 0 Seleção "B". O apostador arriscou que seria Seleção
            "A" 1 x 2 Seleção "B"). O mesmo critério vale para uma partida que termine empatada;</p>
          <p class="lead">4) Em caso de empate na pontuação entre um ou mais participantes, o desempate se dará pelo maior número de acertos exatos dos placares.</p>
          <p align="center">
            <a class="btn btn-lg btn-success" href="https://github.com/bastosbf/easy-bolao" role="button" target="_blank">Baixe Agora!</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
$("#rules-modal").on("show", function() {
	$("#name").focus();
    $("#rules-modal a.btn").on("click", function(e) {
        $("#rules-modal").modal('hide');
    });
});

$("#rules-modal").on("hide", function() { 
    $("#rules-modal a.btn").off("click");
});

$("#rules-modal").on("hidden", function() {
    $("#rules-modal").remove();
});
function showRulesModal() {
   $(document).trigger("clear-alert-id.add");
   $("#rules-modal").modal({                  
     "backdrop"  : "static",
     "keyboard"  : true,
     "show"      : true                   
   });
}
</script>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> bdb5531745f17a5ed34283023ce9b344c75624d7
