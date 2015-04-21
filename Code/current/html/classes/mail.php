<?php
class Email
{

public function NewAccount($emailacc, $url)
{
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$subject = 'Welcome';
$welcomeMessage = "Hello, \r\n\r\nThank you for creating an account! Please click the link below to verify your account. \r\n\r\n" . $url . "\r\n\r\nThanks!";
mail($emailacc, $subject, $welcomeMessage, $headers);
}

public function ForgotPassword($emailacc, $url) 
{
$subject = 'Forgot Password';
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$forgotMessage ="Hello,\r\n\r\nYou have requested to reset your password. Please click the link below to reset your password.\r\n\r\n" .$url." \r\n\r\nThanks!";
mail($emailacc, $subject, $forgotMessage, $headers);
}

public function AddUserToProject($emailacc, $projectName) 
{
$subject = 'You have been added to a Project!';
$url = 'https://plasticcracks.siue.edu/projects.php';
$addMessage = "Hello,\r\n\r\nYou have been added to a project. Please click the link below and navigate to ".$projectName." where you can join the project.\r\n\r\n" . $url . "\r\n\r\nIf you don't have an account you will have to create one with this e-mail address.\r\n\r\nThanks!";
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
mail($emailacc, $subject, $addMessage, $headers);
}

public function EmailGraph($emailacc, $url, $zipcode, $name, $usrmsg) 
{
$subject = 'Plastic Shinkage Cracks';
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$msg = "Hello,\r\n\r\n".$name." has sent you the probablility that concrete will form plastic shinkage cracks for zip code ".$zipcode.".\r\n\r\n".$name." said: ".$usrmsg."\r\n\r\nClick the link below to see the predictions.\r\n\r\n" . $url . "\r\n\r\nThanks!";
mail($emailacc, $subject, $msg, $headers);
} 

public function FutureNotif($emailacc, $projectName, $zipcode, $date) 
{
$subject = "Your predictions are ready!";
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$url = 'https://plasticcracks.siue.edu/projects.php';
$futureMSG = "Hello,\r\n\r\nYour predictions are ready for ".$projectName." at zip code ".$zipcode.".\r\n\r\nClick the link below and select ".$projectName." to view the predictions.\r\n\r\n".$url."\r\n\r\nThanks!";
mail($emailacc, $subject, $futureMSG, $headers);
}

public function ChangeInRiskNotif($emailacc, $projectName, $zipcode, $date, $oldRisk, $newRisk) 
{
$url = 'https://plasticcracks.siue.edu/projects.php';
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$subject = "Change In Risk Notification";
$notifMSG = "Hello,\r\n\r\nThe predicted risk for ".$projectName." on ".$date." has changed from ".$oldRisk." risk to ".$newRisk." risk.\r\n\r\nClick the link below and select view on ".$projectName." to view the changes.\r\n\r\n".$url."\r\n\r\nThanks!";
mail($emailacc, $subject, $notifMSG, $headers);
}

}
?>
