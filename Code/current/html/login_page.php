<!DOCTYPE html>
<!-- saved from url=(0050)http://getbootstrap.com/2.3.2/examples/signin.html -->
<html lang="en">
	<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Sign in</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
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
				<div class="col-xs-12 col-sm-8 col-md-6 col-lg-4 well well-lg">
					<?php
					session_start();
					
					// send user to index if logged in
					if(isset($_SESSION['id']))
						header("Location: /index.php");
						
					echo '
					<div class="alert alert-warning" role="alert">
						Please sign in using your email and password
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					';
					
					include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
					include $_SERVER['DOCUMENT_ROOT']."/html/login.html";
					?>
				</div>
				<div class="col-xs-0 col-sm-2 col-md-3 col-lg-4"></div>
			</div>
		</div>

	</div>
<script id="hiddenlpsubmitdiv" style="display: none;"></script><script>try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); if (d) {for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ d.innerText=i; } } d.dispatchEvent(customEvent); }form.lpsubmitorig2(); } } }}catch(e){}</script></body></html>