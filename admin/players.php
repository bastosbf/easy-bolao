<?php
include '../config/connect.php';

$sql = "SELECT * FROM player";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
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
            <a href="#">Jogadores</a>
          </li>
          <li role="presentation">
            <a href="teams.php">Times</a>
          </li>
          <li role="presentation">
            <a href="games.php">Jogos</a>
          </li>
          <li role="presentation">
            <a href="do_logout.php">Sair</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Administração</h3>
    </div>
    <div class="page-header">
      <h1>Jogadores</h1>
    </div>
    <p align="right">
      <button type="button" class="btn btn-lg btn-primary" onclick="javascript:showAddPlayerModal()">Adicionar Jogador</button>
    </p>
    <?php
		if ($_GET ["success"] == 1) {
			echo '<div class="alert alert-success" role="alert">Joogador adicionado com sucesso!</div>';
		}
	?>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
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
              <td><?=$row["name"]?></td>
              <td align="center">
              	<span class="glyphicon glyphicon-pencil" aria-hidden="true">Editar</span>
              </td>
              <td align="center">
                <span class="glyphicon glyphicon-trash" aria-hidden="true">Excluir</span>
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
  <div id="add-player-modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="add-player-form" action="add_player.php" method="POST">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <label for="name"> Nome </label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Nome" autofocus>
            <span data-alertid="create"></span>
          </div>
          <div class="modal-footer">
            <button id="add-player-button" type="button" class="btn btn-primary">Adicionar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
$("#add-player-modal").on("show", function() {
	$("#name").focus();
    $("#add-player-modal a.btn").on("click", function(e) {
        $("#add-player-modal").modal('hide');
    });
});

$("#add-player-modal").on("hide", function() { 
    $("#add-player-modal a.btn").off("click");
});

$("#add-player-modal").on("hidden", function() {
    $("#add-player-modal").remove();
});

$("#add-player-button").on("click", function() {
    var name = $("#name").val();
    if (name == "") {
         $(document).trigger("set-alert-id-add", [{
                message: 'Digite o nome do jogador!',
         }]);
    } else {
        $("#add-player-form").submit();
    }
});
function showAddPlayerModal() {
   $(document).trigger("clear-alert-id.add");
   $("#add-player-modal").modal({                  
     "backdrop"  : "static",
     "keyboard"  : true,
     "show"      : true                   
   });
}
</script>
</html>