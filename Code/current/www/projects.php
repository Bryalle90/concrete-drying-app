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
        
        <script>
            function ProjPanel(pID) {
                this.projectID = pID;
                
                this.getProjectID = function() {
                    return this.projectID;
                };
                
                this.viewProject = function (projectID) {
                    $.ajax({
                        type: "POST",
                        url: '/php/viewProject.php',
                        data: { projectID: this.projectID },
                        success: function(data) {
                            location.replace('/index.php'+data);
                        }
                    });
                };
                
                this.addUserToProject = function (index) {
                    var element = "addUserEmail-"+index;
                    $.ajax({
                        type: "POST",
                        url: '/php/addUserToProject.php',
                        data: { projectID: this.projectID, email: document.getElementById(element).value },
                        success: function(data) {
                            if(data){ alert(data); }
                            document.getElementById("addUserEmail").value = "";
                        }
                    });
                };
                
                this.editProject = function () {
                    $.ajax({
                        type: "POST",
                        url: '/php/editProject.php',
                        data: { projectID: this.projectID, newName: document.getElementById("editName").value, newZip: document.getElementById("editZip").value },
                        success: function(data) {
                            if(data){ alert(data); }
                            location.replace("/projects.php");
                        }
                    });
                };
                
                this.removeProject = function () {
                    $.ajax({
                        type: "POST",
                        url: '/php/delProject.php',
                        data: { projectID: this.projectID },
                        success: function(data) {
                            if(data){ alert(data); }
                            location.replace("/projects.php");
                        }
                    });
                };
            }
        </script>
    </head>

    <body>
        <div class="container-fluid">
            <?php
            session_start();
            
            // send user to index if not logged in
            if(!isset($_SESSION['id']))
                header("Location: /login_page.php");
                
            include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
            ?>
            <div class="row">
                <div class="col-xs-offset-0 col-xs-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
                    <div class="row">
                        <div class="col-xs-12" align="center">
                            <form class="form-inline" action="/php/addProject.php" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="sr-only" for="nameInput">Project Name</label>
                                        <input style="min-width:200px" name="nameInput" id="nameInput" type="text" class="form-control" placeholder="Project Name">
                                        <select name="unit" class="form-control">
                                            <option>Standard</option>
                                            <option>Metric</option>
                                        </select>
                                        <div class="input-group">
                                            <label class="sr-only" for="zipinput">Zip Code</label>
                                            <input style="min-width:200px" name="zip" id="zipinput" type="zip" class="form-control" pattern="\d{5}" maxLength="5" size="5" placeholder="Zip Code">
                                        </div>
                                        <button class="btn btn-primary" type="submit" name="btn_add">Add Project</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="project-panels row">
                            <?php
                            
                            require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
                            require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbUser.php');
                            $projectdb = new DbProject();
                            $userdb = new DbUser();
                            
                            $projects = $projectdb->getProjects($_SESSION['id']);
                            
                            ?>
                            <script>
                            pPanels = new Array();
                            </script>
                            <?php
                            
                            $index = 0;
                                    
                            if($projects != Null){
                                foreach($projects as $pID => $project){
                                    
                                    ?>
                                    <script>
                                    pPanel = new ProjPanel(<?=$pID?>);
                                    pPanels.push(pPanel);
                                    </script>
                                    <?php
                                    
                                    echo '<div class="col-xs-12 col-sm-6 col-md-4">';
                                    include $_SERVER['DOCUMENT_ROOT']."/html/projectPanel.html";
                                    echo '</div>';
                                    
                                    $index++;
                                }
                            }
                            ?>   
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>