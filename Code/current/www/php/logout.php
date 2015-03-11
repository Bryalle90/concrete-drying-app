<?php
	session_start();
	$_SESSION = array();
	session_destroy();
    $loggedin = 0;
	header("Location: /../index.php");
?>