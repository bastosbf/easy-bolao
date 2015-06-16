<?php
include '../config/connect.php';
include '../config/config.php';
session_unset();
Header ( "Location:index.php" );
?>