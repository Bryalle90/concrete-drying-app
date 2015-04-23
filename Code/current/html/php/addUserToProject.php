<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
	
	session_start();
	
	$projectdb = new DbProject();
	if(isset($_SESSION['id'])){
		$userdb = new DbUser();
		if($_POST['email'] != ''){ // check if email address is blank
			$newUserID = $userdb->isUser($_POST['email']);
			if($newUserID != Null){ // check if user email exists in database
				if($userdb->getIsValidated($newUserID)){ // check if user email is verified
					if(!$projectdb->isUserInProject($_POST['projectID'], $newUserID)){ // check if user is already in project
						$projectdb->addUserToSharedProject($_POST['projectID'], $newUserID, 0);
						require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
						$mailer = new Email();
						$mailer->addUserToProject($email, $projectName);
						
						echo 'user was invited to the project';
					} else {
						echo 'user is already in project or already invited';
					}
				} else {
					echo 'user has not verified thier account';
				}
			} else {
				echo 'user does not have an account';
			}
		} else {
			echo 'you must enter the users email address';
		}
	}
?>