<?php
include '../config/connect.php';
session_start ();
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
          <?php if ($_SESSION ["logged"] == 0) {?>
          <li role="presentation">
            <a href="players.php">Jogadores</a>
          </li>
          <li role="presentation">
            <a href="teams.php">Times</a>
          </li>
          <?php }?>
          <li role="presentation">
            <a href="games.php">Jogos</a>
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
      <div class="jumbotron">
        <h1>Easy Bolão!</h1>
        <p class="lead">Easy Bolão foi criado para gerênciar palpites de jogos de futebol entre vários participantes.</p>
        <h1>Regras</h1>
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
</body>
</html>