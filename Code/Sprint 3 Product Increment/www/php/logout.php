<?php
	session_start();
    // set session array to blank and destroy current session
	$_SESSION = array();
	session_destroy();
    // redirect to index page
	header("Location: /../index.php");
?>