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
        
            <div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-3 col-xs-12 col-sm-8 col-md-6">
                <?php
                session_start();
                
                // send user to index if not logged in
                if(!isset($_SESSION['user']))
                    header("Location: /login_page.php");
                include $_SERVER['DOCUMENT_ROOT']."/php/user.php";
                
                $userdb = new User();
                $userdb->connectdb();
                
                if(isset($_POST['btn_edit'])){ // if the edit button was pressed
                    if($_POST['tb_name'] != ""){
                        $userdb->changeName($_SESSION['id'], $_POST['tb_name']);
                        $_SESSION['user'] = $userdb->getName($_SESSION['id']);
                        echo '
                        <div class="alert alert-success" role="alert">
                            Your display name has been changed
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        ';
                    }
                    if($_POST['tb_email'] != ""){
                        $userdb->changeEmail($_SESSION['id'], $_POST['tb_email']);
                        $_SESSION['email'] = $userdb->getEmail($_SESSION['id']);
                        echo '
                        <div class="alert alert-success" role="alert">
                            Your email address has been changed and an email has been sent to the new email to verify
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        ';
                    }
                    if($_POST['tb_newPass'] != ""){
                        if($_POST['tb_pass'] != "" || $_POST['tb_pass2'] != ""){
                            if($_POST['tb_pass'] == $_POST['tb_pass2'] && $userdb->verifyLogin($_SESSION['email'], $_POST['tb_pass']) == $_SESSION['id']){
                                $userdb->changePassword($_SESSION['id'], $_POST['tb_newPass']);
                                echo '
                                <div class="alert alert-success" role="alert">
                                    Your password has been changed
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                ';
                            }
                        } else {
                            echo '
                            <div class="alert alert-danger" role="alert">
                                You must enter your old password to change it to the new password
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            ';
                        }
                    }
                }
                include $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
                ?>
                <form class="form-horizontal" action="/edit.php" method="post">
                    <div class="form-group row">
                        <label for="tb_name" class="col-sm-2 control-label">Display Name</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="tb_name" value="" placeholder="<?=$_SESSION['user']?>"></div>
                    </div>
                    <div class="form-group row">
                        <label for="tb_email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="tb_email" value="" placeholder="<?=$_SESSION['email']?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tb_pass" class="col-sm-2 control-label">Old Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="tb_pass" placeholder="*******">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tb_pass2" class="col-sm-2 control-label">Retype Old Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="tb_pass2" placeholder="*******">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tb_newPass" class="col-sm-2 control-label">New Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="tb_newPass" placeholder="*******">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary" type="submit" name="btn_edit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </body>
</html>