<?php
include '../config/connect.php';

if ($_SESSION ["logged"] == null) {
	Header ( "Location:login.php" );
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
          <li role="presentation" class="active">
            <a href="#">Início</a>
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
          <li role="presentation">
            <a href="do_logout.php">Sair</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Administração</h3>
      <div class="jumbotron" align="center">
        <h1>Easy Bolão!</h1>
        <p class="lead">Easy Bolão foi criado para gerênciar palpites de jogos de futebol entre diferentes jogadores. As regras....</p>
        <p>
          <a class="btn btn-lg btn-success" href="https://github.com/bastosbf/easy-bolao" role="button" target="_blank">Baixe Agora!</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>