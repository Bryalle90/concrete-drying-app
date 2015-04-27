<?php
	session_start();
	$_SESSION = array();
	session_destroy();
	?><script> window.location.replace("/../index.php"); </script><?php
	exit();
?>