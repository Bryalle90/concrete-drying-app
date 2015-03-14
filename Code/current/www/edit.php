<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Edit Account</title>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
        
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="bootstrap/css/theme.css" rel="stylesheet">
    </head>

    <body>
        <?php
        session_start();
        
        // send user to index if not logged in
        if(!isset($_SESSION['user']))
            header("Location: /index.php");
            
        include $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
        ?>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="newEmail" placeholder="<?=$_SESSION['user']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Old Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="oldPass" placeholder="*******">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">New Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="newPass" placeholder="*******">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
            </div>
        </div>
    </body>
</html>