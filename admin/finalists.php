<?php
include '../config/connect.php';
include '../config/config.php';
if ($_SESSION ["logged"] != 0) {
	Header ( "Location:login.php" );
}

$sql = "SELECT * FROM team ORDER BY name";
$result = mysql_query ( $sql );
$num_results = mysql_num_rows ( $result );
$options = "";
for($i = 1; $i <= $num_results; $i ++) {
	$row = mysql_fetch_array ( $result );
	$options .= '<option value="' . $row ["id"] . '">' . $row ["name"] . '</option>';
}
$sql = "SELECT * FROM finalist INNER JOIN team ON finalist.id_team = team.id ORDER BY position";
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
            <a href="#">Jogadores</a>
          </li>
          <li role="presentation">
            <a href="teams.php">Times</a>
          </li>
          <li role="presentation">
            <a href="games.php">Jogos</a>
          </li>
          <li role="presentation">
            <a href="guesses.php">Palpites</a>
          </li>
          <li role="presentation" class="active">
            <a href="#">Finalistas</a>
          </li>
          <li role="presentation">
            <a href="do_logout.php">Sair</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Administração</h3>
    </div>
    <div class="page-header">
      <h1>Finalistas</h1>
    </div>
    <p align="right">
      <button type="button" class="btn btn-lg btn-primary" onclick="javascript:showAddFinalistModal()">Adicionar Finalista</button>
    </p>
    <?php
	if ($_GET ["added"] == 1) {
		echo '<div class="alert alert-success" role="alert">Finalista adicionado com sucesso!</div>';
	}
	?>
	<?php
	if ($_GET ["error"] == 1) {
		echo '<div class="alert alert-danger" role="alert">Posição ou finalista já cadastrado!</div>';
	}
	?>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Finalista</th>
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
              <td><?=$row["position"]?>º</td>
              <td><?=$row["name"]?></td>
              <td align="center">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
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
  <div id="add-finalist-modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="add-finalist-form" action="add_finalist.php" method="POST">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <label for="position"> Posição </label>
            <input type="text" class="form-control" name="position" id="position" placeholder="Posição" autofocus>
            <label for="team"> Time </label>
            <select class="form-control" id="team" name="team">
             <?=$options?>	
            </select>
            <span data-alertid="create"></span>
          </div>
          <div class="modal-footer">
            <button id="add-finalist-button" type="button" class="btn btn-primary">Adicionar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
$("#add-finalist-modal").on("show", function() {
	$("#name").focus();
    $("#add-finalist-modal a.btn").on("click", function(e) {
        $("#add-finalist-modal").modal('hide');
    });
});

$("#add-finalist-modal").on("hide", function() { 
    $("#add-finalist-modal a.btn").off("click");
});

$("#add-finalist-modal").on("hidden", function() {
    $("#add-finalist-modal").remove();
});

$("#add-finalist-button").on("click", function() {
    var name = $("#name").val();
    if (name == "") {
         $(document).trigger("set-alert-id-add", [{
                message: 'Digite a posição do time!',
         }]);
    } else {
        $("#add-finalist-form").submit();
    }
});
function showAddFinalistModal() {
   $(document).trigger("clear-alert-id.add");
   $("#add-finalist-modal").modal({                  
     "backdrop"  : "static",
     "keyboard"  : true,
     "show"      : true                   
   });
}
</script>
</html>