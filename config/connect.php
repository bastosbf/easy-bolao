<?php
$server = "localhost:3306";
$db = "u170290546_bolao";
$user = "u170290546_bolao";
$password = "senhabolao";

//Conectando
$conn = @mysql_connect($server, $user, $password); 
@mysql_select_db($db, $conn) 
or die("Erro ao se conectar com o banco de dados!"); 
?>