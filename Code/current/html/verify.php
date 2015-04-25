<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Plastic Crack Risk Calculator - Verify Account</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="libraries/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="libraries/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libraries/bootstrap/css/theme.css" rel="stylesheet">
		<?php session_start(); ?>
	</head>

	<body style="background-color: #DBDBDB">		
		<div class="container-fluid">
		
			<div class="col-sm-offset-2 col-md-offset-3 col-xs-12 col-sm-8 col-md-6">
			
				<div class="alert alert-success" role="alert" id="alertSuccessVerified" hidden="true">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Your account has been verified!
				</div>
				<div class="alert alert-danger" role="alert" id="alertDangerNotVerified" hidden="true">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					The code could not be verified. Try sending a new verification email.
				</div>
				<div class="alert alert-success" role="alert" id="alertSuccessEmailSent" hidden="true">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					A new verification email has been sent to your address!
				</div>
				<div class="alert alert-danger" role="alert" id="alertDangerAlreadyVerified" hidden="true">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					This email address has already been verified
				</div>
				<div class="alert alert-danger" role="alert" id="alertDangerNoAccount" hidden="true">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					This email address does not have an account
				</div>
				
				<?php

				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');

				$userdb = new DbUser();
				$userID = 0;

				// if $_GET['code'] is not blank check validation table for code and validate account
				if(isset($_GET['code'])){
					$userID = $userdb->checkCode($_GET['email'], $_GET['code']);

					if($userID){
						// set verified in userdb
						$userdb->validate($userID);
						require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbLog.php');
						$logger = new DbLog();
						$logger->addUserVerified();

						?><script>$('#alertSuccessVerified').show()</script><?php
					} else {
						?><script>$('#alertDangerNotVerified').show()</script><?php
					}
				}

				// if user tried to log in
				if(isset($_POST['btn_signin']) || $userID){					
					if(!$userID)
						$userID = $userdb->verifyLogin($_POST['tb_email'], $_POST['tb_pass']);

					if($userID != Null){
						$_SESSION = array();
						session_destroy();
						session_start();
						
						$projectdb = new DbProject();
						
						$_SESSION['id'] = $userID;
						$_SESSION['user'] = $userdb->getName($userID);
						$_SESSION['email'] = $userdb->getEmail($userID);
						$_SESSION['admin'] = $userdb->isUserAdmin($userID);
						$_SESSION['verified'] = $userdb->getIsValidated($userID);
						$_SESSION['resetPW'] = $userdb->getForceNewPass($userID);
						$_SESSION['unacceptedProjects'] = count($projectdb->getUnacceptedProjects($userID));
					} else {
						?><script> window.location.replace("/login_page.php"); </script><?php
						exit();
					}
				}

				if(isset($_POST['btn_resend'])){
					if(!$userID)
						$userID = $userdb->isUser($_POST['tb_email']);

					if($userID){
						if(!$userdb->getIsValidated($userID)){

							$email = $_POST['tb_email'];
							$code = $userdb->changeCode($userID);

							$link = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
							$link = $link.'/verify.php?email='.$email.'&code='.$code;
							
							require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
							$mailer = new Email();
							$mailer->newAccount($email, $link, $code);
							
							?><script>$('#alertSuccessEmailSent').show()</script><?php
						} else {
							?><script>$('#alertDangerAlreadyVerified').show()</script><?php
						}
					} else {
						?><script>$('#alertDangerNoAccount').show()</script><?php
					}
				}

				// send user to projects if verified already
				if(isset($_SESSION['verified']) && $_SESSION['verified'] && !isset($_GET['code'])){
					if($_SESSION['resetPW']){
						?><script> window.location.replace("/edit.php"); </script><?php
						exit();
					} else {
						?><script> window.location.replace("/projects.php"); </script><?php
						exit();
					}
				}

				include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";

				?>

				<div class="well well-lg row <?php echo (isset($_SESSION['verified']) && $_SESSION['verified']) ? 'hidden' : '' ?>" align="center">
					<h4>Verify your account</h4>
					Please click the link sent to your email or enter the validation code from your email below.
					<form class="form-inline col-xs-12" action="/verify.php" method="get">
						<div class="form-group">
							<div class="input-group">
								<input type="email" class="form-control" name="email" value="<?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : ''?>" placeholder="Email Address">
								<input type="text" class="form-control" name="code" value="" placeholder="Validation Code">
								<button class="btn btn-primary" type="submit" id="btn_validate">Enter</button>
							</div>
						</div>
					</form>
				</div>

				<div class="well well-lg row <?php echo (isset($_SESSION['verified']) && $_SESSION['verified']) ? 'hidden' : '' ?>" align="center">
					<h4>Send new verification email</h4>
					<form class="form-inline col-xs-12" action="/verify.php" method="post">
						<div class="form-group">
							<div class="input-group">
								<input type="email" class="form-control" name="tb_email" value="<?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : ''?>" placeholder="Email Address">
								<span class="input-group-btn">
									<button class="btn btn-info" type="submit" name="btn_resend">Send</button>
								</span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>