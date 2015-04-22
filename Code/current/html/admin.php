<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Admin</title>
		
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
			<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-3 col-xs-12 col-sm-8 col-md-6">
			<?php
				session_start();
					
				// send user to index if not logged in
				if(!isset($_SESSION['id'])){
					?><script> window.location.replace("/login_page.php"); </script><?php
					exit();
				}
				// send user to verify if not verified
				else if(!$_SESSION['verified']){
					?><script> window.location.replace("/verify.php"); </script><?php
					exit();
				}
				else if($_SESSION['resetPW']){
					?><script> window.location.replace("/edit.php"); </script><?php
					exit();
				}
				// send user to index if not an admin
				else if(!$_SESSION['admin'])
					?><script> window.location.replace("/index.php"); </script><?php
					exit();
				
				include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
			?>
			</div>
		</div>
	</body>
</html>