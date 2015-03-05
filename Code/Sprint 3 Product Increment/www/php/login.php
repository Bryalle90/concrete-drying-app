<?php
    require $_SERVER['DOCUMENT_ROOT']."/php/usersdb.php";
    // require $_SERVER['DOCUMENT_ROOT']."/php/User.php";
		
	session_start();
	// if user tried to log in
	if(isset($_POST['btn_signin']) && $_POST['tb_email'] != "" && $_POST['tb_pass'] != ""){
		// $user = new User();
        // $user.connectdb();
        $userEmail = $_POST['tb_email'];
        
		// get user hash from database
        // $hash = user.getPassHash($userEmail);
        
        // the testing password is 'pass'.. only temporary
        $user = new UsersDB();
		$hash = $user->hashPass('pass');
        
		if($user->verifyPass($_POST['tb_pass'], $hash)){
            // set session array to blank and destroy current session
			$_SESSION = array();
			session_destroy();
            // start new session
			session_start();
			$_SESSION['user'] = $userEmail;
			// redirect to index page
			header("Location: /../index.php");
		}
	}
	if(!isset($_SESSION['user'])){
		include $_SERVER['DOCUMENT_ROOT']."/includes/login.html";
	} else {
		header("Location: /../index.php");
	}

?>