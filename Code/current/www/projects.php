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
        <style>
            .project-panels > .col-md-4:nth-child(3n+1) {
                clear: both;
            }
            .btn-xl {
                padding: 18px 28px;
                font-size: 32px; //change this to your desired size
                line-height: normal;
                -webkit-border-radius: 8px;
                   -moz-border-radius: 8px;
                        border-radius: 8px;
            }
            .addbtn {
                margin-bottom: 20px;
            }
        </style>

        <script>
            function ProjPanel(pID, i) {
                this.projectID = pID;
                this.index = i;
                
                this.getProjectID = function() {
                    return this.projectID;
                };
                
                this.viewProject = function () {
                    $.ajax({
                        type: "POST",
                        url: '/php/viewProject.php',
                        data: { projectID: this.projectID },
                        success: function(data) {
                            location.replace('/index.php'+data);
                        }
                    });
                };
                
                this.addUserToProject = function () {
                    $.ajax({
                        type: "POST",
                        url: '/php/addUserToProject.php',
                        data: { projectID: this.projectID, email: document.getElementById("addUserEmail-"+this.index).value },
                        success: function(data) {
                            if(data){ alert(data); }
                            document.getElementById("addUserEmail").value = "";
                        }
                    });
                };
                
                this.editProject = function () {
                    var formElement = $('<form action="/projects.php" method="post" style="display:none;"><input type="hidden" name="edit" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /><input type="hidden" name="newName" value="'+document.getElementById("editName-"+this.index).value+'" /><input type="hidden" name="newZip" value="'+document.getElementById("editZip-"+this.index).value+'" /></form>');
                    $('body').append(formElement);
                    $(formElement).submit();
                    
                };
                
                this.removeProject = function () {
                    var formElement = $('<form action="/projects.php" method="post" style="display:none;"><input type="hidden" name="remove" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /></form>');
                    $('body').append(formElement);
                    $(formElement).submit();
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
            require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DataService.php');
            require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
            require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbUser.php');
            ?>
            <div class="row">
                <div class="col-xs-offset-0 col-xs-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
                    <div class="addbtn row">
                        <div class="col-xs-12" align="center">
                            <button class="btn btn-primary btn-xl" type="button" data-toggle="modal" data-target="#newProjectModal">Add Project</button>
                        </div>
                    </div>
                    <div class="project-panels row">
                        <?php
                
                        if(isset($_POST['btn_addProject'])){
                            if($_POST['newProjectZip'] != ""){
                                $dataService = new DataService((int)$_POST['newProjectZip']);
                                $city = $dataService->getCity();
                                $state = $dataService->getState();
                                
                                if($city != Null && $state != Null){
                                    $location = $city.', '.$state;
                                    $title = $_POST['newProjectName'] == '' ? $location : $_POST['newProjectName'];
                                    $projectdb = new DbProject();
                                    $projectdb->addToProjectTable($title, $location, $_SESSION['id'], (int)$_POST['newProjectZip'], date('Y-m-d H:i:s', strtotime('now')), $_POST['newProjectUnit']);
                                    
                                    echo '
                                    <div class="alert alert-success" role="alert">
                                        Your new project has been added!
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                } else {
                                    echo '
                                    <div class="alert alert-danger" role="alert">
                                        invalid zipcode: Could not get coordinates for zip code
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                }
                            } else {
                                echo '
                                <div class="alert alert-danger" role="alert">
                                    You must enter a zip code to create a project
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                ';
                            }
                        }
                        if(isset($_POST['remove'])){
                            $projectdb = new DbProject();
                            if($projectdb->isUserInProject($_POST['projectID'], $_SESSION['id'])){
                                $projectdb->deleteProject($_POST['projectID'], $_SESSION['id']);
                                echo '
                                <div class="alert alert-success" role="alert">
                                    Your project has been removed!
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                ';
                            } else {
                                echo '
                                <div class="alert alert-danger" role="alert">
                                    error: you are not in the project
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                ';
                            }
                        }
                        if(isset($_POST['edit'])){
                            $projectdb = new DbProject();
                            if($projectdb->getOwner($_POST['projectID']) == $_SESSION['id']){
                                if($_POST['newName'] != ''){
                                    $projectdb->changeProjectName($_POST['projectID'], $_POST['newName']);
                                    echo '
                                    <div class="alert alert-success" role="alert">
                                        The name for your project has been changed
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                }
                                if($_POST['newZip'] != ''){
                                    
                                    $dataService = new DataService((int)$_POST['newZip']);
                                    $city = $dataService->getCity();
                                    $state = $dataService->getState();
                                    
                                    if($city != Null && $state != Null){
                                        $projectdb->changeProjectLocation($_POST['projectID'], $city.', '.$state);
                                        $projectdb->changeProjectZip($_POST['projectID'], $_POST['newZip']);
                                        
                                        echo '
                                        <div class="alert alert-success" role="alert">
                                            The zip code for your project has been changed
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        ';
                                    } else {
                                        echo '
                                        <div class="alert alert-danger" role="alert">
                                            error: unable to get coordinates from zip code
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        ';
                                    }
                                }
                            } else {
                                echo '
                                <div class="alert alert-danger" role="alert">
                                    error: only the owner of the project can edit
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                ';
                            }
                        }
                        
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
                                pPanel = new ProjPanel(<?=$pID?>,<?=$index?>);
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

    <!-- New Project Modal -->
    <div class="modal fade" id="newProjectModal" tabindex="-1" role="dialog" aria-labelledby="newProjectLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content container-fluid">
                <form class="form-horizontal" action="/projects.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="newProjectLabel">Create New Project</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="newProjectName" class="control-label">Project Name</label>
                                    <input name="newProjectName" type="text" class="form-control" placeholder="Project Name">
                                </div>
                                <div class="form-group">
                                    <label for="newProjectZip" class="control-label">Zip Code</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">*</span>
                                        <input name="newProjectZip" type="zip" class="form-control" pattern="\d{5}" maxLength="5" size="5" placeholder="zip code">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="newProjectUnit" class="control-label">Unit</label>
                                    <select name="newProjectUnit" class="form-control">
                                        <option>Standard</option>
                                        <option>Metric</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-xs-6" align="left">
                                <button class="btn btn-primary" type="submit" name="btn_addProject">Add Project</button>
                            </div>
                            <div class="col-xs-6" align="right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</html>