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
        <div class="container-fluid">
            <?php
            session_start();
            
            // send user to index if not logged in
            if(!isset($_SESSION['id']))
                header("Location: /index.php");
                
            include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
            ?>
            <div class="row">
                <div class="col-xs-0 col-sm-0 col-md-1 col-lg-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8">
                    <div class="col-xs-12" align="center">
                        <form class="form-inline" action="/php/addProject.php" method="post">
                            <div class="form-group">
                                <select name="unit" class="form-control">
                                    <option>Standard</option>
                                    <option>Metric</option>
                                </select>
                                <div class="input-group">
                                    <label class="sr-only" for="zipinput">Zip Code</label>
                                    <input style="min-width:200px" name="zip" id="zipinput" type="zip" class="form-control" pattern="\d{5}" maxLength="5" size="5" placeholder="zip code">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" name="btn_add">Add Project</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                        <?php
                        
                        require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/Project.php');
                        require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/User.php');
                        $projectdb = new Project();
                        $projectdb->connectdb();
                        $userdb = new User();
                        $userdb->connectdb();
                        
                        $projects = $projectdb->getProjects($_SESSION['id']);
                        
                        if($projects != Null){
                            foreach($projects as $project){                        
                                echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">';
                                include $_SERVER['DOCUMENT_ROOT']."/html/projectPanel.html";
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                <div class="col-xs-0 col-sm-0 col-md-1 col-lg-2"></div>
            </div>
        </div>
    </body>
</html>