<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Plastic Crack Risk Calculator - Create Account</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="libraries/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="libraries/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libraries/bootstrap/css/theme.css" rel="stylesheet">
		<?php
			session_start();
					
			// send user to index if logged in
			if(isset($_SESSION['user'])){
				?><script> window.location.replace("/index.php"); </script><?php
				exit();
			}
		?>
	</head>

	<body style="background-color: #DBDBDB">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
				
					<div class="alert alert-success" id="alertCreated" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Your account has been created and an email has been sent to verify your address
					</div>
					<div class="alert alert-danger" id="alertNoMatch" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Passwords do not match
					</div>
					<div class="alert alert-danger" id="alertAlready" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Email address is already in use
					</div>
					<div class="alert alert-danger" id="alertFillAll" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Please fill all fields to continue
					</div>
				
					<?php
						include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
						include($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
					
						if(isset($_POST['btn_create'])){ // if the create button was pressed
							if($_POST['tb_email'] != "" && $_POST['tb_pass'] != ""	&& $_POST['tb_pass2'] != "" ){ // if email, pass, pass2 fields not blank
								$userdb = new DbUser();
								
								if($userdb->isUser($_POST['tb_email']) == Null){ // email not already used
									if ($_POST['tb_pass'] == $_POST['tb_pass2']){
										$userID = $userdb->addUser(($_POST['tb_name'] != "" ? $_POST['tb_name'] : $_POST['tb_email']), $_POST['tb_email'], $_POST['tb_pass'], 'n');
										$code = $userdb->changeCode($userID);
										$email = $_POST['tb_email'];

										$link = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
										$link = $link.'/verify.php?email='.$email.'&code='.$code;
										
										require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
										$mailer = new Email();
										$mailer->newAccount($email, $link, $code);
										
										require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbLog.php');
										$logger = new DbLog();
										$logger->addUserCreated();

										?><script>$('#alertCreated').show()</script><?php
									} else { // passwords did not match
										?><script>$('#alertNoMatch').show()</script><?php
									}
								} else {
									?><script>$('#alertAlready').show()</script><?php
								}
							} else { // some fields are empty
								?><script>$('#alertFillAll').show()</script><?php
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
							<div class="row" align="center">								
								<a class="btn btn-default pull-left" type="button" data-toggle="modal" title="why" data-target="#why">Why?</a>
								<button class="btn btn-primary pull-right" type="submit" name="btn_create">Create Account</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
	
	<!-- Why Modal -->
	<div class="modal fade" id="why" tabindex="-1" role="dialog" aria-labelledby="whyModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="whyLabel;">Why you should create an account?</h4>
				</div>
				<div class="modal-body">
					<h4>Creating an account has four major advantages</h4>
					<p>1. You can save a zipcode you visit often as a project</p>
					<p>2. You can share projects with other users</p>
					<p>3. You can recieve notifications if the risk changes for one of your projects</p>
					<p>4. You can be notified when your project becomes in the prediction range</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</html>