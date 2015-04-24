<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Create Account</title>
		
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
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-0 col-sm-2 col-md-3 col-lg-4"></div>
				<div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
				
					<?php
					session_start();
					
					// send user to index if logged in
					if(isset($_SESSION['user']))
						header("Location: /index.php");
						
					include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
					include($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
					
					if(isset($_POST['btn_create'])){ // if the create button was pressed
						if($_POST['tb_email'] != "" && $_POST['tb_pass'] != ""	&& $_POST['tb_pass2'] != "" ){ // if email, pass, pass2 fields not blank
							$userdb = new DbUser();
							
							if($userdb->isUser($_POST['tb_email']) == Null){ // email not already used
								if ($_POST['tb_pass'] == $_POST['tb_pass2']){
									$userID = $userdb->addUser(($_POST['tb_name'] != "" ? $_POST['tb_name'] : $_POST['tb_email']), $_POST['tb_email'], $_POST['tb_pass'], 'n');
									$code = $userdb->getCode($userID);
									$email = $_POST['tb_email'];

									$link = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
									$link = $link.'/verify.php?email='.$email.'&code='.$code;
									
									require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
									$mailer = new Email();
									$mailer->newAccount($email, $link, $code);

									echo '
									<div class="alert alert-success" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										Your account has been created and an email has been sent to verify your address
									</div>
									';
								} else { // passwords did not match
									echo '
									<div class="alert alert-danger" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										Passwords do not match
									</div>
									';
								}
							} else {
								echo '
								<div class="alert alert-danger" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Email address is already in use
								</div>
								';
							}
						} else { // some fields are empty
							echo '
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Please fill all fields to continue
							</div>
							';
						}
					}

					?>
					<div class=" well well-lg row">
						<form class="form-horizontal col-sm-12" action="/create.php" method="post">
							<div class="form-group row">
								<label for="tb_name" class="control-label">Display Name</label>
								<input type="text" class="form-control" name="tb_name" value="<?php echo (isset($_POST['tb_name']) ? $_POST['tb_name'] : "");?>" placeholder="Display Name">
							</div>
							<div class="form-group row">
								<label for="tb_email" class="control-label">Email</label>
								<div class="input-group">
									<span class="input-group-addon">*</span>
									<input type="email" class="form-control" name="tb_email" value="<?php echo (isset($_POST['tb_email']) ? $_POST['tb_email'] : "");?>" placeholder="Email Address">
								</div>
							</div>
							<div class="form-group row">
								<label for="tb_pass" class="control-label">Password</label>
								<div class="input-group">
									<span class="input-group-addon">*</span>
									<input type="password" class="form-control" name="tb_pass" placeholder="*******">
								</div>
							</div>
							<div class="form-group row">
								<label for="tb_pass2" class="control-label">Retype Password</label>
								<div class="input-group">
									<span class="input-group-addon">*</span>
									<input type="password" class="form-control" name="tb_pass2" placeholder="*******">
								</div>
							</div>
							<div align="center" class="row">
								<button class="btn btn-primary" type="submit" name="btn_create">Create Account</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-xs-0 col-sm-2 col-md-3 col-lg-4"></div>
			</div>
		</div>
	</body>
</html>