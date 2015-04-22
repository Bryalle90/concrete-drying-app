<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Forgot Password</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="bootstrap/css/theme.css" rel="stylesheet">
	</head>
	
	<body style="background-color: #DBDBDB">
		<?php
		session_start();
		
		// send user to index if not logged in
		if(isset($_SESSION['id'])){
			?><script> window.location.replace("/index.php"); </script><?php
			exit();
		}
			
		include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
		?>
			<div class="container-fluid">
				<div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
				<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
					<?php
						
					require($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
					$userdb = new DbUser();

					if(isset($_GET['code'])){
						$userID = $userdb->checkForgotCode($_GET['email'], $_GET['code']);

						if($userID){
							$newPass = $userdb->resetPass($userID);
							
							// TODO: send email with new password
							echo '
							<div class="alert alert-success" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								A new password has been sent to your account '; echo $newPass; echo '
							</div>
							';
						} else {
							echo '
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								The code could not be verified. Try sending a new email.
							</div>
							';
						}
					}
					
					if (isset($_POST['btn_forgotSubmit'])){
						
						$userID = $userdb->isUser($_POST['tb_forgotEmail']);
						if($userID){
							$email = $_POST['tb_forgotEmail'];
							$code = $userdb->createForgotCode($userID);

							//TODO: send email to user with link and code
							$link = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
							$link = $link.'/forgot.php?email='.$email.'&code='.$code;
							
							echo '
							<div class="alert alert-success" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								An email has been sent to the address provided. '; echo $link; echo '
							</div>
							';
						} else {
							echo '
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								No account with this email address exists.
							</div>
							';
						}
					}
					?>
					<div class="well well-lg" align="center">
						<form class="form-inline" action="#" method="post">
							<h3 class="form-heading">Please enter your email</h3>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">*</div>
									<input type="email" class="form-control" name="tb_forgotEmail" placeholder="email address" style="background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
									<span class="input-group-btn">
										<button type="submit" name="btn_forgotSubmit" class="btn btn-primary">Submit</button>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
			</div>
		</div>
	</body>
</html>