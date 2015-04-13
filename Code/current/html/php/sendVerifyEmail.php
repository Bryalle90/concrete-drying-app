<?php						
$verifydb = new DbUserVerification();
$code = $verifydb->addUser($userID);

//TODO: send email to user with link and code
$link = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
$link = $link.'/verify.php?code='.$code;
?>