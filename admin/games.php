<?php
include '../config/connect.php';
session_start ();
if ($_SESSION ["logged"] != 0 && $_SESSION ["logged"] != 1) {
	Header ( "Location:login.php" );
}

$sql = "SELECT * FROM game ORDER BY date";
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
          <?php if ($_SESSION ["logged"] == 0) {?>
          <li role="presentation">
            <a href="players.php">Jogadores</a>
          </li>
          <li role="presentation">
            <a href="teams.php">Times</a>
          </li>
          <?php }?>
          <li role="presentation" class="active">
            <a href="#">Jogos</a>
          </li>
          <?php if ($_SESSION ["logged"] == 0) {?>
          <li role="presentation">
            <a href="guesses.php">Palpites</a>
          </li>
          <?php }?>
          <li role="presentation">
            <a href="do_logout.php">Sair</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Administração</h3>
    </div>
    <div class="page-header">
      <h1>Jogos</h1>
    </div>
    <?php if ($_SESSION ["logged"] == 0) {?>
    <p align="right">
      <button type="button" class="btn btn-lg btn-primary" onclick="javascript:showAddGameModal()">Adicionar Jogo</button>
    </p>
    <?php }?>
    <?php
	if ($_GET ["added"] == 1) {
		echo '<div class="alert alert-success" role="alert">Jogo adicionado com sucesso!</div>';
	}
	?>
	<?php
	if ($_GET ["edited"] == 1) {
		echo '<div class="alert alert-success" role="alert">Placar alterado com sucesso!</div>';
	}
	?>
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
              <th></th>
              <th></th>
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
              <td><?php if($row["score_1"] != -1) echo $row["score_1"]; else { echo "-"; }?></td>
              <td><?php if($row["score_2"] != -1) echo $row["score_2"]; else { echo "-"; }?></td>
              <td><?=$teams[$row["team_2"]]?></td>
              <td><?=$row["date"]?></td>
              <td align="center">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true" onclick="javascript:showEditGameModal(<?=$row["id"]?>, '<?=$teams[$row["team_1"]]?>', '<?=$teams[$row["team_2"]]?>', <?=$row["score_1"]?>, <?=$row["score_2"]?> );"></span>
              </td>
              <td align="center">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
              </td>
            </tr>
            <?php
												}
												?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div id="add-game-modal" class="modal fade">
  	<?php
	$sql = "SELECT * FROM team ORDER BY name";
	$result = mysql_query ( $sql );
	$num_results = mysql_num_rows ( $result );
	$options = "";
	for($i = 1; $i <= $num_results; $i ++) {
		$row = mysql_fetch_array ( $result );
		$options .= '<option value="' . $row ["id"] . '">' . $row ["name"] . '</option>';
	}
	?>
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="add-game-form" action="add_game.php" method="POST">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <label for="team_1"> Time 1 </label>
            <select class="form-control" id="team_1" name="team_1">
             <?=$options?>	
            </select>
            <label for="team_2"> Time 2 </label>
            <select class="form-control" id="team_2" name="team_2">
              <?=$options?>	
            </select>
            <label for="date"> Data (dd/mm/aaaa - hh:mm) </label>
            <input type="text" class="form-control" name="date" id="name" placeholder="dd/mm/aaaa - hh:mm" autofocus>
            <span data-alertid="create"></span>
          </div>
          <div class="modal-footer">
            <button id="add-game-button" type="button" class="btn btn-primary">Adicionar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="edit-game-modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="edit-game-form" action="edit_game.php" method="POST">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <p align="center">
              <label id="teams"></label>
            </p>
            <input type="hidden" id="game" name="game" />
            <label for="name"> Placar 1 </label>
            <input type="text" class="form-control" name="score_1" id="score_1" autofocus>
            <label for="name"> Placar 2 </label>
            <input type="text" class="form-control" name="score_2" id="score_2">
            <span data-alertid="create"></span>
          </div>
          <div class="modal-footer">
            <button id="edit-game-button" type="button" class="btn btn-primary">Editar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
$("#add-game-modal").on("show", function() {
	$("#name").focus();
    $("#add-game-modal a.btn").on("click", function(e) {
        $("#add-game-modal").modal('hide');
    });
});

$("#add-game-modal").on("hide", function() { 
    $("#add-game-modal a.btn").off("click");
});

$("#add-game-modal").on("hidden", function() {
    $("#add-game-modal").remove();
});

$("#add-game-button").on("click", function() {
    var name = $("#name").val();
    if (name == "") {
         $(document).trigger("set-alert-id-add", [{
                message: 'Digite o nome do jogador!',
         }]);
    } else {
        $("#add-game-form").submit();
    }
});
function showAddGameModal() {
   $(document).trigger("clear-alert-id.add");
   $("#add-game-modal").modal({                  
     "backdrop"  : "static",
     "keyboard"  : true,
     "show"      : true                   
   });
}
$("#edit-game-modal").on("show", function() {
	$("#name").focus();
    $("#edit-game-modal a.btn").on("click", function(e) {
        $("#edit-game-modal").modal('hide');
    });
});

$("#edit-game-modal").on("hide", function() { 
    $("#edit-game-modal a.btn").off("click");
});

$("#edit-game-modal").on("hidden", function() {
    $("#edit-game-modal").remove();
});

$("#edit-game-button").on("click", function() {
    $("#edit-game-form").submit();
});
function showEditGameModal(id, team_1, team_2, score_1, score_2) {
   $("#game").val(id);
   var teams =  team_1 + " x " + team_2;
   $("#teams").text(teams);
   if(score_1 != -1) {
   	$("#score_1").val(score_1);
   }
   if(score_2 != -1) {
   	$("#score_2").val(score_2);
   }
   $(document).trigger("clear-alert-id.edit");
   $("#edit-game-modal").modal({                  
     "backdrop"  : "static",
     "keyboard"  : true,
     "show"      : true                   
   });
}
</script>
</html>