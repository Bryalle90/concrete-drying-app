<?php
    include $_SERVER['DOCUMENT_ROOT']."/php/user.php";
	// if user tried to log in
	if(isset($_POST['btn_signin']) && $_POST['tb_email'] != "" && $_POST['tb_pass'] != ""){
		$userdb = new User();
        $userdb->connectdb();
        $userID = $userdb->verifyLogin($_POST['tb_email'], $_POST['tb_pass']);
        if($userID != Null){
            session_start();
			$_SESSION = array();
			session_destroy();
			session_start();
            
			$_SESSION['id'] = $userID;
			$_SESSION['user'] = $userdb->getName($userID);
			$_SESSION['email'] = $userdb->getEmail($userID);
        }
	}
	if(!isset($_SESSION['user'])){
        header("Location: /../login_page.php");
		//include $_SERVER['DOCUMENT_ROOT']."/includes/login_page.html";
	} else {
		header("Location: /../index.php");
	}
?>