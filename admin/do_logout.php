<?php
include '../config/connect.php';
$_SESSION ["logged"] = null;
Header ( "Location:index.php" );
?>