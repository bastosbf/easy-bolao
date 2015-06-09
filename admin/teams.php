<?php
include '../config/connect.php';

$sql = "SELECT * FROM team ORDER BY name";
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
          <li role="presentation">
            <a href="teams.php">Jogadores</a>
          </li>
          <li role="presentation" class="active">
            <a href="#">Times</a>
          </li>
          <li role="presentation">
            <a href="games.php">Jogos</a>
          </li>
          <li role="presentation">
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
      <h1>Times</h1>
    </div>
    <p align="right">
      <button type="button" class="btn btn-lg btn-primary" onclick="javascript:showAddTeamModal()">Adicionar Time</button>
    </p>
    <?php
	if ($_GET ["added"] == 1) {
		echo '<div class="alert alert-success" role="alert">Time adicionado com sucesso!</div>';
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
  <div id="add-team-modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="add-team-form" action="add_team.php" method="POST">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <label for="name"> Nome </label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Nome" autofocus>
            <span data-alertid="create"></span>
          </div>
          <div class="modal-footer">
            <button id="add-team-button" type="button" class="btn btn-primary">Adicionar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
$("#add-team-modal").on("show", function() {
	$("#name").focus();
    $("#add-team-modal a.btn").on("click", function(e) {
        $("#add-team-modal").modal('hide');
    });
});

$("#add-team-modal").on("hide", function() { 
    $("#add-team-modal a.btn").off("click");
});

$("#add-team-modal").on("hidden", function() {
    $("#add-team-modal").remove();
});

$("#add-team-button").on("click", function() {
    var name = $("#name").val();
    if (name == "") {
         $(document).trigger("set-alert-id-add", [{
                message: 'Digite o nome do time!',
         }]);
    } else {
        $("#add-team-form").submit();
    }
});
function showAddTeamModal() {
   $(document).trigger("clear-alert-id.add");
   $("#add-team-modal").modal({                  
     "backdrop"  : "static",
     "keyboard"  : true,
     "show"      : true                   
   });
}
</script>
</html>