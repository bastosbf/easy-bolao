<?php
include '../config/connect.php';
session_start ();
session_unset();
Header ( "Location:index.php" );
?>