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
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                
                    <?php
                    session_start();
                    
                    // send user to index if logged in
                    if(isset($_SESSION['user']))
                        header("Location: /index.php");
                        
                    include_once $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
                    
                    if(isset($_POST['btn_create'])){
                        if($_POST['tb_email'] != "" && $_POST['tb_pass'] != ""  && $_POST['tb_pass2'] != "" ){
                            // if($_POST['newEmail'] not in database already)
                                if ($_POST['tb_pass'] == $_POST['tb_pass2']){
                                    // hash password
                                    // add email and password hash to user database
                                    echo '
                                    <div class="alert alert-success" role="alert">
                                        Your account has been created and an email has been sent to verify your address
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                } else { // passwords did not match when creating account
                                    echo '
                                    <div class="alert alert-danger" role="alert">
                                        Passwords do not match
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                }
                        } else { // some fields are empty
                            echo '
                            <div class="alert alert-danger" role="alert">
                                Please fill all fields to continue
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            ';
                        }
                    }
                    ?>
                    
                    <form class="form-horizontal" action="/create.php" method="post">
                        <div class="form-group">
                            <label for="tb_email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="tb_email" value="<?=(isset($_POST['tb_email']) ? $_POST['tb_email'] : "")?>" placeholder="Email Address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tb_pass" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="tb_pass" placeholder="*******">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tb_pass2" class="col-sm-2 control-label">Retype Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="tb_pass2" placeholder="*******">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" name="btn_create">Create Account</button>
                    </form>
                    
                </div>
                <div class="col-xs-0 col-sm-2 col-md-3 col-lg-3"></div>
            </div>
        </div>
    </body>
</html>