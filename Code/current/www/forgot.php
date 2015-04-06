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
        if(isset($_SESSION['user']))
            header("Location: /index.php");
            
        include "/html/navbar.html";
        ?>
            <div class="container-fluid">
                <div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                    <?php
                    if (isset($_POST['btn_forgotSubmit']))
                        echo '
                        <div class="alert alert-success" role="alert">
                            An email has been sent to the address provided
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        ';
                    ?>
                    <div align="center">
                        <form class="form-inline" action="#" method="post">
                            <h3 class="form-heading">Please enter your email</h3>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">*</div>
                                    <input type="text" class="form-control" name="tb_forgotEmail" placeholder="email@address.com" autocomplete="off" style="background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
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