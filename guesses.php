<?php
include 'config/connect.php';
include 'config/config.php';
if (!$_SESSION ["last"]) {
	Header ( "Location:index.php" );
}

$sql = "SELECT * FROM player";
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

$sql = "SELECT * FROM game ORDER BY date";
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
          <li role="presentation" class="active">
            <a href="#">Palpites</a>
          </li>
        </ul>
      </nav>
    </div>
    <div class="page-header">
      <h1>Palpites</h1>
    </div>
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
          <input type="submit" class="btn btn-default" value="Visualizar Palpites" />
        </span>
      </div>
    </form>
    <div class="page-header">
      <h1>Jogos</h1>
    </div>
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
			for($i = 1; $i <= $game_num_results; $i ++) {
				$row = mysql_fetch_array ( $game_result );
			?>			
            <tr>
              <td><?=$i?></td>
              <td><?=$teams[$row["team_1"]]?></td>
              <td><?php if($row["score_1"] != -1) echo $row["score_1"]; else { echo "-"; }?></td>
              <td><?php if($row["score_2"] != -1) echo $row["score_2"]; else { echo "-"; }?></td>
              <td><?=$teams[$row["team_2"]]?></td>
              <td><?=$row["date"]?></td>              
            </tr>
            <?php
												}
												?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>