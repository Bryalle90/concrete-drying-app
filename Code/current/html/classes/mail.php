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

public function AddUserToProject($username, $url) 
{
$subject = 'You have been added to a Project!';
$addMessage = "Hello,\r\n\r\nYou have been added to a project. Please click the link below and log in to view the project.\r\n\r\n" . $url . "\r\n\r\nIf you don't have an account you will have to create one.\r\n\r\nThanks!";
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
mail($username, $subject, $addMessage, $headers);
}

public function EmailGraph($username, $url, $zipcode, $name, $usrmsg) 
{
$subject = 'Plastic Shinkage Cracks';
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$msg = "Hello,\r\n\r\n".$name." has sent you the probablility that concrete will form plastic shinkage cracks for ".$zipcode.".\r\n\r\nUser added message: ".$usrmsg."\r\n\r\nClick the link below to see the predictions.\r\n\r\n" . $url . "\r\n\r\nThanks!";
mail($username, $subject, $msg, $headers);
} 

public function FutureNotif($username, $url, $zipcode, $date) 
{
$subject = "Your predictions are ready!";
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$futureMSG = "Hello,\r\n\r\nYour concrete plastic shrinkage crack predictions are ready for ".$zipcode." on ".$date." to view. \r\n\r\nClick the link below to view the predictions.\r\n\r\n".$url."\r\n\r\nThanks!";
mail($username, $subject, $futureMSG, $headers);
}

public function ChangeInRiskNotif($username, $zipcode, $time, $date, $risk) 
{
$url = 'https://plasticcracks.siue.edu/projects.php';
$headers = 'From: plasticcracks@siue.edu' . "\r\n";
$subject = "PROJECT Notiofcation";
$notifMSG = "Hello,\r\n\r\nThe predicted risk of plastic shinkage cracks prediction changed for ".$zipcode." at ".$time." and now has a ".$risk." risk".$date.".\r\n\r\nClick the link below to view the changes.\r\n\r\n".$url."\r\n\r\nThanks!";
mail($username, $subject, $notifMSG, $headers);
}

}
?>
