<?php
class Email
{
	public function newAccount($email, $url, $code)
	{
		$verifyURL = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
		$verifyURL = $verifyURL.'/verify.php';
		
		$subject = 'Welcome';
		$body = "Hello, \r\n\r\nThank you for creating an account! Please click the link below to verify your account. If you did not create an account at this site, you can simply ignore this message.\r\n\r\n" . $url . 
					"\r\n\r\nIf you are unable to visit the URL, please go to ".$verifyURL." and enter your email address and code: ".$code.
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}
	
	public function newEmailVerify($email, $url, $code)
	{
		$subject = 'Email Change Verification';
		$body = "Hello, \r\n\r\nWe just wanted to verify that you intended to change your email. Please click the link below to change your email address for your account to this address.".
					"If you did not request to change your email, you can simply ignore this message.\r\n\r\n" . $url .
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}
	
	public function forgotVerify($email, $url)
	{
		$subject = 'Password Reset Verification';
		$body = "Hello, \r\n\r\nWe just wanted to verify that you intended to reset your password. Please click the link below to have a new password generated and sent to you. If you did not request a new password, you can simply ignore this message.\r\n\r\n" . $url .
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}

	public function forgotPassword($email, $pass) 
	{
		$url = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
		$url = $url.'/login_page.php';
		
		$subject = 'New Password';
		$body ="Hello,\r\n\r\nYou have requested to reset your password. You may now log in with the following password: ".$pass."\r\n\r\n  Once you log in you will be prompted to change your password.\r\n\r\n" .$url.
							"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}

	public function addUserToProject($email, $projectName) 
	{
		$url = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
		$url = $url.'/projects.php';
		
		$subject = 'You have been invited to a Project!';
		$body = "Hello,\r\n\r\nYou have been invited to a project. Please click the link below and navigate to ".$projectName." where you can accept or decline the project invite. ".
					"If you accept the invite you will receive notifications and reminders that are set for this project.\r\n\r\n" . $url.
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}

	public function emailGraph($email, $url, $zipcode, $name, $usrmsg) 
	{
		$subject = 'Plastic Shinkage Cracks';
		$body = "Hello,\r\n\r\n".$name." has sent you the probablility that concrete will form plastic shinkage cracks for zip code: ".$zipcode.".\r\n\r\n".$name." said: ".addslashes($usrmsg)."\r\n\r\nClick the link below to see the predictions.\r\n\r\n" . $url .
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	} 

	public function futureNotif($email, $projectName, $zipcode) 
	{
		$url = 'https://plasticcracks.siue.edu/projects.php';
		
		$subject = "Project Reminder";
		$body = "Hello,\r\n\r\nThis is your reminder for project: ".$projectName." at zip code: ".$zipcode.".\r\n\r\nClick the link below and click the view button under ".$projectName." to view the predictions.\r\n\r\n".$url.
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}

	public function databaseReset($email)
	{
		$subject = "Plastic Shrinkage Cracks Account Deleted";
		$body = "Hello, \r\n\r\nWe had to reset the database. All accounts, projects, and notifications have been deleted. You will have to create a new account.\r\n\r\nSorry for the inconvenience. ";
		$this->sendMessage($email, $subject, $body);
	}

	public function changeInRiskNotif($email, $projectName, $zipcode, $date, $oldRisk, $newRisk) 
	{
		$url = 'https://plasticcracks.siue.edu/projects.php';
		
		$d = explode('-', $date);
		$date = $d[0].'-'.$d[1].'-'.$d[2].'T'.$d[3].':00:00';
		$time = date('D j M Y \a\t Hi', strtotime($date));
		
		$subject = "Change In Risk Notification";
		$body = "Hello,\r\n\r\nThe predicted risk for ".$projectName." on ".$time." has changed from ".$oldRisk." risk to ".$newRisk." risk.\r\n\r\nThe notification for this point has been removed. ".
					"If you want further reminders for this point you will have to create a new notification.".
					"\r\n\r\nClick the link below and click the view button under ".$projectName." to view the changes.\r\n\r\n".$url.
					"\r\n\r\nThanks!";
		$this->sendMessage($email, $subject, $body);
	}
	
	public function sendMessage($email, $subject, $body){
		$header = 'From: plasticcracks@siue.edu' . "\r\n";
		mail($email, $subject, $body, $header);
	}
}
?>
