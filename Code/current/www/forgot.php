<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="bryalle.duckdns.org">
		<title>Forgot Password</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
        <?php
            // start session
            session_start();
            $_SESSION = array();
            session_destroy();
            $loggedin = 0;
            include $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
            if (isset($_POST['btn_forgotSubmit']))
                echo '<div class="alert alert-success" role="alert">An email has been sent to the address provided</div>';
            include $_SERVER['DOCUMENT_ROOT']."/includes/forgot.html";
        ?>
    </body>
</html>