<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Projects</title>
        
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
            
        include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
        ?>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-0 col-sm-0 col-md-1 col-lg-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8">
                    <?php
                    for ($i = 0; $i < 10; $i++) {
                        $title = "Panel Title ".$i;
                        $content = "Panel Content ".$i;
                        
                        echo '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">';
                        include $_SERVER['DOCUMENT_ROOT']."/html/projectPanel.html";
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="col-xs-0 col-sm-0 col-md-1 col-lg-2"></div>
            </div>
        </div>
    </body>
</html>