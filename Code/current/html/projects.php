<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Projects</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="libraries/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<!-- Bootstrap theme -->
		<link href="libraries/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libraries/bootstrap/css/theme.css" rel="stylesheet">
		<link href="libraries/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
		
		<?php session_start(); ?>
		
		<style>
			.btn-xl {
				padding: 18px 28px;
				font-size: 32px; //change this to your desired size
				line-height: small;
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
				
				this.viewProject = function (unit, zip) {
					var formElement = $('<form action="/index.php?unit='+unit+'&zip='+zip+'" method="post" style="display:none;"><input type="hidden" name="view" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /></form>');
					$('body').append(formElement);
					$(formElement).submit();
				};
				
				this.addUserToProject = function () {
					$.ajax({
						type: "POST",
						url: '/php/addUserToProject.php',
						data: { projectID: this.projectID, email: document.getElementById("addUserEmail-"+this.index).value },
						success: function(data) { if(data){ alert(data); } }
					});
				};
				
				this.editProject = function () {
					var formElement = $('<form action="/projects.php" method="post" style="display:none;"><input type="hidden" name="edit" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /><input type="hidden" name="newName" value="'+document.getElementById("editName-"+this.index).value+'" /><input type="hidden" name="newZip" value="'+document.getElementById("editZip-"+this.index).value+'" /><input type="hidden" name="newUnit" value="'+document.getElementById("editUnit-"+this.index).value+'" /><input type="hidden" name="newReminder" value="'+document.getElementById("editReminder-"+this.index).value+'" /></form>');
					$('body').append(formElement);
					$(formElement).submit();
					
				};
				
				this.removeProject = function () {
					var formElement = $('<form action="/projects.php" method="post" style="display:none;"><input type="hidden" name="remove" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /></form>');
					$('body').append(formElement);
					$(formElement).submit();
				};
				
				this.acceptInvite = function () {
					var formElement = $('<form action="/projects.php" method="post" style="display:none;"><input type="hidden" name="accept" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /></form>');
					$('body').append(formElement);
					$(formElement).submit();
				};
				
				this.declineInvite = function () {
					var formElement = $('<form action="/projects.php" method="post" style="display:none;"><input type="hidden" name="decline" value=""/><input type="hidden" name="projectID" value="'+this.projectID+'" /></form>');
					$('body').append(formElement);
					$(formElement).submit();
				};
			}
		</script>
	</head>

	<body style="background-color: #DBDBDB">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-offset-0 col-xs-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
					<?php
					
					// send user to index if not logged in
					if(!isset($_SESSION['id'])){
						?><script> window.location.replace("/login_page.php"); </script><?php
						exit();
					}
					// send user to verify if not verified
					else if(!$_SESSION['verified']){
						?><script> window.location.replace("/verify.php"); </script><?php
						exit();
					}
					else if($_SESSION['resetPW']){
						?><script> window.location.replace("/edit.php"); </script><?php
						exit();
					}
						
					require($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');
					require($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
					require($_SERVER['DOCUMENT_ROOT'].'/classes/DataService.php');
					require($_SERVER['DOCUMENT_ROOT'].'/classes/WeatherService.php');
								
					$projectdb = new DbProject();
					$userdb = new DbUser();
						
					if(isset($_POST['btn_addProject'])){
						if($_POST['newProjectZip'] != ""){
							$dataService = new DataService((int)$_POST['newProjectZip']);
							$city = $dataService->getCity();
							$state = $dataService->getState();
							
							if($city != Null && $state != Null){
								$weatherService = new WeatherService($_POST['newProjectZip'], $dataService->getLat(), $dataService->getLon());
								try{
									$weatherService->getWeatherData();
									$location = $city.', '.$state;
									$title = $_POST['newProjectName'] == '' ? $location : $_POST['newProjectName'];
									$unit = $_POST['newProjectUnit'] == 'Standard' ? 'S' : 'M';
									$zip = (int)$_POST['newProjectZip'];
									$time = $_POST['reminder'];
									
									require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbLog.php');
									$logger = new DbLog();
									$logger->addProjectCreated($zip);
									if($_POST['reminder'] != ''){
										$logger->addReminderCreated($zip, $time);
									}
									
									$projectdb->addToProjectTable($title, $location, $_SESSION['id'], $zip, $unit, $time);
									
									echo '
									<div class="alert alert-success" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										Your new project has been added!
									</div>
									';
								}
								catch (Exception $error){
									echo '
									<div class="alert alert-danger" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										invalid zipcode: Could not get data for zip code
									</div>
									';
								}
							} else {
								echo '
								<div class="alert alert-danger" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									invalid zipcode: Could not get coordinates for zip code
								</div>
								';
							}
						} else {
							echo '
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								You must enter a zip code to create a project
							</div>
							';
						}
					}
					if(isset($_POST['remove'])){
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
							if($projectdb->getUnit($_POST['projectID']) != $_POST['newUnit'][0]){
								$projectdb->changeProjectUnit($_POST['projectID'], $_POST['newUnit']);
								echo '
								<div class="alert alert-success" role="alert">
									The unit for your project has been changed
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								';
							}
							if($projectdb->getReminder($_POST['projectID']) != $_POST['newReminder']){
								$projectdb->changeReminder($_POST['projectID'], $_POST['newReminder']);
								$time = $_POST['newReminder'];
								
								if($time != ''){
									$zip = $projectdb->getZipcode($_POST['projectID']);
									require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbLog.php');
									$logger = new DbLog();
									$logger->addReminderCreated($zip, $time);
								}
								echo '
								<div class="alert alert-success" role="alert">
									The reminder for your project has been changed
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								';
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
					if(isset($_POST['accept']) && $projectdb->isUserInProject($_POST['projectID'], $_SESSION['id'])){
						$projectdb->acceptProject($_POST['projectID'], $_SESSION['id']);
					}
					if(isset($_POST['decline']) && $projectdb->isUserInProject($_POST['projectID'], $_SESSION['id'])){
						$projectdb->deleteProject($_POST['projectID'], $_SESSION['id']);
					}
					
					$_SESSION['unacceptedProjects'] = count($projectdb->getUnacceptedProjects($_SESSION['id']));
					$numProjects = count($projectdb->getProjectIDs($_SESSION['id']));
					include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
					?>
					<div class="addbtn row">
						<div class="col-xs-12" align="center">
							<button class="btn btn-primary btn-xl popover-show" type="button" data-toggle="modal" data-target="#newProjectModal" title="Create a new project" data-trigger="focus click" data-container="body" data-content="You have no projects, click here to add one" data-placement="bottom">Add Project</button>
							<script>
								// if there are no projects we show a popup telling the user to add one
								if(<?php echo $numProjects ?> < 1){
									$('.popover-show').popover('show');
								}
							</script>
						</div>
					</div>
					<script>pPanels = new Array();</script>
					<div class="panel-group col-xs-12 col-sm-offset-2 col-sm-8" id="accordion3" role="tablist" aria-multiselectable="false">
						<?php
						$projects = $projectdb->getProjects($_SESSION['id']);
						
						if($projects){
							$index = 0;
							echo '';
							foreach($projects as $pID => $project){
								?>
								<script>
									pPanel = new ProjPanel(<?php echo $pID?>,<?php echo $index?>);
									pPanels.push(pPanel);
								</script>
								<?php
								include $_SERVER['DOCUMENT_ROOT']."/html/projectPanel3.html";
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
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="newProjectLabel">Create New Project</h4>
				</div>
				<form class="form-horizontal" action="/projects.php" method="post">
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
								<div class="form-group">
									<label for="reminder" class="control-label">Remind me</label>
									<div class="input-group date form_date col-xs-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="reminder" data-link-format="yyyy-mm-dd">
										<input class="form-control" type="text" name="reminder" value="" readonly>
										<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-xs-7" align="left">
								<button class="btn btn-primary" type="submit" name="btn_addProject">Add Project</button>
							</div>
							<div class="col-xs-5" align="right">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="libraries/bootstrap/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
	<script>
	$('.form_date').datetimepicker({
		language:  'en',
		startDate: new Date(),
		weekStart: 0,
		autoclose: 1,
		startView: 3,
		minView: 2,
	});
	</script>
</html>