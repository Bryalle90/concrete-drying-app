<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');
	// if user tried to log in
	if(isset($_POST['btn_signin']) && $_POST['tb_email'] != "" && $_POST['tb_pass'] != ""){
		$userdb = new DbUser();
		
		$userID = $userdb->verifyLogin($_POST['tb_email'], $_POST['tb_pass']);
		if($userID != Null){
			session_start();
			$_SESSION = array();
			session_destroy();
			session_start();
			$projectdb = new DbProject();
			
			$_SESSION['id'] = $userID;
			$_SESSION['user'] = $userdb->getName($userID);
			$_SESSION['email'] = $userdb->getEmail($userID);
			$_SESSION['admin'] = $userdb->isUserAdmin($userID);
			$_SESSION['numProjects'] = count($projectdb->getProjects($userID));
		}
	}
	if(!isset($_SESSION['user'])){
		header("Location: /../login_page.php");
	} else {
		header("Location: /../projects.php");
	}
?>