<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Plastic Crack Risk Calculator - Edit Account</title>
		
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
					
			// send user to login_page if not logged in
			if(!isset($_SESSION['user'])){
				?><script> window.location.replace("/login_page.php"); </script><?php
				exit();
			} 

			// send user to verify if not verified
			if(!$_SESSION['verified']){
				?><script> window.location.replace("/verify.php"); </script><?php
				exit();
			}
		?>
	</head>

	<body style="background-color: #DBDBDB">		
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
					<div class="alert alert-success" id="alertSuccessNameChange" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Your display name has been changed
					</div>
					<div class="alert alert-success" id="alertSuccessEmailChange" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						An email has been sent to the new address to verify your new email address
					</div>
					<div class="alert alert-success" id="alertSuccessPassChange" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Your password has been changed
					</div>
					<div class="alert alert-danger" id="alertFailNewPassNoMatch" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Your new password did not match
					</div>
					<div class="alert alert-danger" id="alertFailOldPassEmpty" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						You must enter your old password to change it
					</div>
					<div class="alert alert-danger" id="alertFailPassIncorrect" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Your password was not correct
					</div>
					<div class="alert alert-danger" id="alertFailPassNoMatch" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Passwords did not match
					</div>
					<div class="alert alert-danger" id="alertFailBlankPassDelete" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						You must enter your password to delete your account
					</div>
					<div class="alert alert-danger" id="alertForcePassChange" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						You must change your password
					</div>
					<div class="alert alert-danger" id="alertAlready" role="alert" hidden="true">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Email address is already in use
					</div>
					
					<?php
						require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
						require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');
						
						$userdb = new DbUser();
						$projectdb = new DbProject();
						
						if(isset($_POST['btn_edit1'])){ // if the edit button was pressed
							if($_POST['tb_name'] != ""){
								$userdb->changeName($_SESSION['id'], $_POST['tb_name']);
								$_SESSION['user'] = $userdb->getName($_SESSION['id']);
								?><script>$('#alertSuccessNameChange').show()</script><?php
							}
							if($_POST['tb_email'] != ""){
								$email = $_POST['tb_email'];
								if(!$userdb->isUser($email)){
									$code = $userdb->changeCode($_SESSION['id']);

									$link = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').htmlspecialchars("://$_SERVER[HTTP_HOST]", ENT_QUOTES, 'UTF-8');
									$link = $link.'/verify.php?email='.$_SESSION['email'].'&newemail='.$email.'&code='.$code;
									
									require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
									$mailer = new Email();
									$mailer->newEmailVerify($email, $link, $code);
									?><script>$('#alertSuccessEmailChange').show()</script><?php
								} else {
									?><script>$('#alertAlready').show()</script><?php
								}
							}
						}
						if(isset($_POST['btn_edit2'])){ // if the edit button was pressed
							if($_POST['tb_newPass'] != "" || $_POST['tb_newPass2'] != ""){
								if($_POST['tb_pass'] != ""){
									if($userdb->verifyLogin($_SESSION['email'], $_POST['tb_pass']) == $_SESSION['id']){
										if($_POST['tb_newPass'] == $_POST['tb_newPass2']){
											$userdb->changePassword($_SESSION['id'], $_POST['tb_newPass']);
											$_SESSION['resetPW'] = 0;
											?><script>$('#alertSuccessPassChange').show()</script><?php
										} else {
											?><script>$('#alertFailNewPassNoMatch').show()</script><?php
										}
									} else {
										?><script>$('#alertFailPassIncorrect').show()</script><?php
									}
								} else {
									?><script>$('#alertFailOldPassEmpty').show()</script><?php
								}
							}
						}
						if(isset($_POST['btn_delete'])){
							if($_POST['tb_delpass'] != "" && $_POST['tb_delpass2'] != ""){
								if($_POST['tb_delpass'] == $_POST['tb_delpass2']){
									if($userdb->verifyLogin($_SESSION['email'], $_POST['tb_delpass']) == $_SESSION['id']){
										$projects = $projectdb->getProjectIDs($_SESSION['id']);
										foreach($projects as $proj){
											$projectdb->deleteProject($proj, $_SESSION['id']);
										}
										$userdb->deleteUser($_SESSION['id']);
										header("Location: /php/logout.php");
									} else {
										?><script>$('#alertFailPassIncorrect').show()</script><?php
									}
								} else {
									?><script>$('#alertFailPassNoMatch').show()</script><?php
								}
							} else {
								?><script>$('#alertFailBlankPassDelete').show()</script><?php
							}
						}
						if($_SESSION['resetPW']){
							?><script>$('#alertForcePassChange').show()</script><?php
						}
						include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
					?>
					
					<div class="well well-lg row">
						<form class="form-horizontal" action="/edit.php" method="post">
							<label for="tb_name" class="control-label">Display Name</label>
							<input type="text" class="form-control" name="tb_name" value="" placeholder="<?php echo $_SESSION['user']?>">
							
							<label for="tb_email" class="control-label">Email</label>
							<input type="email" class="form-control" name="tb_email" value="" placeholder="<?php echo $_SESSION['email']?>">
							<br>
							<button class="btn btn-primary center-block" type="submit" name="btn_edit1">Submit</button>
						</form>
					</div>
					
					<div class="well well-lg row">
						<form class="form-horizontal" action="/edit.php" method="post">
							<label for="tb_pass" class="control-label">Old Password</label>
							<input type="password" class="form-control" name="tb_pass" placeholder="*******">
							
							<label for="tb_newPass" class="control-label">New Password</label>
							<input type="password" class="form-control" name="tb_newPass" placeholder="*******">

							<label for="tb_newPass2" class="control-label">Retype New Password</label>
							<input type="password" class="form-control" name="tb_newPass2" placeholder="*******">
							<br>
							<button class="btn btn-primary center-block" type="submit" name="btn_edit2">Submit</button>
						</form>
					</div>
								
					<button class="btn btn-danger center-block" type="button" data-toggle="modal" data-target="#delModal">Delete Account</button>
					
				</div>
			</div>
		</div>
	</body>
	
	<!-- Delete Account Modal -->
	<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" action="/edit.php" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="delModalLabel">Are you sure you want to delete your account?</h4>
					</div>
					<div class="modal-body">
					
						<div class="form-group row">
							<label for="tb_pass" class="col-sm-3 control-label">Password</label>
							<div class="col-sm-9">
								<input type="password" class="form-control" name="tb_delpass" placeholder="*******">
							</div>
						</div>
						<div class="form-group row">
							<label for="tb_pass2" class="col-sm-3 control-label">Retype Password</label>
							<div class="col-sm-9">
								<input type="password" class="form-control" name="tb_delpass2" placeholder="*******">
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<button class="btn btn-danger" type="submit" name="btn_delete">I'm sure</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</html>