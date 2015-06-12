<?php
include '../config/connect.php';
session_start ();
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$sql = "SELECT * FROM player";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );

$sql = "SELECT * FROM game";
$game_result = mysql_query ( $sql );
$game_num_results = mysql_num_rows ( $game_result );
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
            <a href="#">Palpites</a>
          </li>
          <li role="presentation">
            <a href="do_logout.php">Sair</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Administração</h3>
    </div>
    <div class="page-header">
      <h1>Palpites</h1>
    </div>
    <?php
	if ($_GET ["added"] == 1) {
		echo '<div class="alert alert-success" role="alert">Palpites adicionados com sucesso!</div>';
	}
	if ($_GET ["edited"] == 1) {
		echo '<div class="alert alert-success" role="alert">Palpite alterados com sucesso!</div>';
	}
	?>
    <form action="list_guesses.php" method="post">
      <div class="input-group">
        <select class="form-control" id="player" name="player">
		    <?php
			for($i = 1; $i <= $num_results; $i ++) {
				$row = mysql_fetch_array ( $result );
				
				$sql = "SELECT * FROM guess WHERE id_player = " . $row ["id"];
				$guess_result = mysql_query ( $sql );
				$guess_num_results = mysql_num_rows ( $guess_result );
			?>
			<option <?php if($guess_num_results == $game_num_results) { echo 'style="background-color: #D3D3D3;"';} ?> value="<?=$row["id"]?>"><?=$row["name"]?></option>
		  	<?php
			}
			?>
	  	  </select>
        <span class="input-group-btn">
          <input type="submit" class="btn btn-default" value="Adicionar Palpites" />
        </span>
      </div>
    </form>
  </div>
</body>
</html>