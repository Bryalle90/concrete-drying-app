<?php
	require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";
		
	session_start();
	// if user tried to log in
	if(isset($_POST['btn_signin']) && $_POST['tb_email'] != "" && $_POST['tb_pass'] != ""){
		// $usersdb = new UsersDB();
		// get user hash from database
		$hash = '$2y$10$O5ugtbPvNmq.dZmscAitq.pHlm.Ibthasn3.szvvDGPuoXuI4zUN2';
		if(password_verify($_POST['tb_pass'], $hash)){
			$user = $_POST['tb_email'];
			
			$_SESSION = array();
			session_destroy();
			session_start();
			$_SESSION['user'] = $user;
			
			header("Location: /../index.php");
		}
	}
	if(!isset($_SESSION['user'])){
		include $_SERVER['DOCUMENT_ROOT']."/includes/login.html";
	} else {
		header("Location: /../index.php");
	}
?>