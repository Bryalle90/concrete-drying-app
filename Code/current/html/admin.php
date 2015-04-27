<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Plastic Crack Risk Calculator - Admin</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="libraries/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="libraries/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libraries/bootstrap/css/theme.css" rel="stylesheet">
		<link href="libraries/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">


		<style>
			.btn-xl {
				padding: 18px 28px;
				font-size: 32px; //change this to your desired size
				line-height: small;
				-webkit-border-radius: 8px;
				   -moz-border-radius: 8px;
						border-radius: 8px;
			}			
		</style>
	</head>
	
	<body style="background-color: #DBDBDB">	
		<?php
			session_start();

			// send user to index if not admin
			if(!$_SESSION['admin'])
				header("Location: /index.php");

			include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
			
		?>
		<div align="center">
			<div class="btn row">
				<form action="/admin.php" method="post" >			
					<button class="btn btn-primary btn-xl" name="btn_totalstats" type="submit" title="View Total Stats">Total Stats</button>					
					
					<button class="btn btn-primary btn-xl" type="button" data-toggle="modal" title="View Range of Stats" data-target="#dateRange">Ranged Stats</button>
				
					<button class="btn btn-primary btn-xl" name="btn_promote" type="submit" title="Promote User">Promote User</button>
					
					<button class="btn btn-primary btn-xl" name="btn_reset" type="submit" title="Reset System">System Reset</button>
				</form>
			</div>
		</div>	
		<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">

			<div class="alert alert-success" role="alert" id="alertUserPromoted" hidden="true">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				User Promoted
			</div>
			<div class="alert alert-danger" role="alert" id="alertAlreadyAdmin" hidden="true">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				User Already Admin
			</div>
			<div class="alert alert-danger" role="alert" id="alertUserDoesNotExist" hidden="true">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				User Does Not Exist
			</div>
		</div>

		<?php
			if(isset($_POST['btn_totalstats'])){				
				include $_SERVER['DOCUMENT_ROOT']."/classes/DbAdmin.php";

				$admindb = new DbAdmin();

				echo '<div class ="well well-lg col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
				<h1>	
					<div class = "text-center">	
						Total Stats
						<br>				
					</div>
				</h1>';
				echo '<h3>
					<table class="table table-striped">
						<tr class="info">
							<td>';											
								echo 'Accounts';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr>
							<td>';											
									echo 'Created: ';								
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfAccounts();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}								
							echo '</td>
						</tr>
						<tr>
							<td>';											
									echo 'Validated: ';								
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfAccountsVal();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}								
							echo '</td>
						</tr>						
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Projects';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr>
							<td>';
								echo 'Created: ';
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfProjects();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td>
						</tr>	
						<tr>
							<td>';
								echo 'Shared: ';
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfSharedProjects();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td>
						</tr>						
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Future Notifications';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>				
						<tr>
							<td>';
								echo 'Created: ';
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfFutureNotificationsCreated();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td>
						</tr>						
						<tr>
							<td>';
								echo 'Sent: ';
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfFutureNotificationsSent();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td>
						</tr>
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Change In Risk Notifications';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>

						<tr>
							<td>';
								echo 'Created: ';								
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfChangeInStateNotificationsCreated();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td
						<tr>		
						<tr>
							<td>';
								echo 'Sent: ';								
							echo '</td>
							<td>';
								$result = $admindb->getTotalNumberOfChangeInStateNotificationsSent();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td
						</tr>	
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Zip Code';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>

						<tr>
							<td>';
								echo 'Searched By Guests: ';								
							echo '</td>
							<td>';
								$result = $admindb->getZipGuest();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td
						<tr>		
						<tr>
							<td>';
								echo 'Searched By User: ';								
							echo '</td>
							<td>';
								$result = $admindb->getZipUser();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td
						</tr>
						<tr>
							<td>';
								echo 'Searched By Project: ';								
							echo '</td>
							<td>';
								$result = $admindb->getZipProject();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							echo '</td
						</tr>											
					</table>
				</h3>
				</div>';
			}

			if(isset($_POST['btn_reset']) || isset($_POST['btn_yes'])){

				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbAdmin.php');
				
				if(isset($_POST['btn_yes'])){ // if the create button was pressed
						if($_POST['tb_email'] != "" && $_POST['tb_pass'] != ""	&& $_POST['tb_pass2'] != "" ){ // if email, pass, pass2 fields not blank
							
							require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
							$userdb = new DbUser();
							$admindb = new DbAdmin();
							$email = new Email();
							

							if ($_POST['tb_pass'] == $_POST['tb_pass2']){

								$emails = $admindb->getAllEmails();

								for($i = 0; $i < count($emails); $i++){
									$email->databaseReset($emails[$i]);	
								}
									
								$admindb->dropAll();	
	
								$userID = $userdb->addUser(($_POST['tb_name'] != "" ? $_POST['tb_name'] : $_POST['tb_email']), $_POST['tb_email'], $_POST['tb_pass'], 'y');
								$userdb->validate($userID);

								echo '
								<div class="alert alert-success" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Your account has been created and the database has been cleaned.'; echo '
								</div>
								';
							} else { // passwords did not match
								echo '
								<div class="alert alert-danger" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Passwords do not match
								</div>
								';
							}							
						} else { // some fields are empty
							echo '
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Please fill all fields to continue
							</div>
							';
						}
					}

					?>
					<div class=" well well-lg row col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
						<form class="form-horizontal col-sm-12" action="/admin.php" method="post">
							<div class="form-group row">
								<label for="tb_name" class="control-label">Display Name</label>
								<input type="text" class="form-control" name="tb_name" value="<?php echo (isset($_POST['tb_name']) ? $_POST['tb_name'] : "");?>" placeholder="Display Name">
							</div>
							<div class="form-group row">
								<label for="tb_email" class="control-label">Email</label>
								<div class="input-group">
									<span class="input-group-addon">*</span>
									<input type="email" class="form-control" name="tb_email" value="<?php echo (isset($_POST['tb_email']) ? $_POST['tb_email'] : "");?>" placeholder="Email Address">
								</div>
							</div>
							<div class="form-group row">
								<label for="tb_pass" class="control-label">Password</label>
								<div class="input-group">
									<span class="input-group-addon">*</span>
									<input type="password" class="form-control" name="tb_pass" placeholder="*******">
								</div>
							</div>
							<div class="form-group row">
								<label for="tb_pass2" class="control-label">Retype Password</label>
								<div class="input-group">
									<span class="input-group-addon">*</span>
									<input type="password" class="form-control" name="tb_pass2" placeholder="*******">
								</div>
							</div>
							<div align="center" class="row">
								<a class="btn btn-danger" type="button" data-toggle="modal" title="Reset System" data-target="#youSure">Reset System</a>
							</div>
							<!-- Are You Sure Modal -->
							<div class="modal fade" id="youSure" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="youSureLabe;">Are you sure you want to reset the system?</h4>
										</div>
										<div class="modal-body">
											<?php
												echo 'This will delete all information stored.';
											?>
										</div>
										<div class="modal-footer">
											<input class="btn btn-danger" name="btn_yes" type="submit"/>
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				<?php
			}

			if(isset($_POST['btn_sure'])){			

				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbAdmin.php');
	
				$admindb = new DbAdmin();

				if(isset($_POST['dateFirst'])){
					$rangeFirst = $_POST['dateFirst'];
				}else{
					$rangeFirst = '2015-04-23';
				}

				if(isset($_POST['dateSecond'])){
					$rangeSecond = $_POST['dateSecond'];
				}else{
					$rangeSecond = '2015-04-23';

				}

			
					if(isset($_POST['dateFirst'])){
						$rangeFirst = $_POST['dateFirst'];
					}else{
						$rangeFirst = '2015-04-23';

					}
					if(isset($_POST['dateSecond'])){
						$rangeSecond = $_POST['dateSecond'];
					}else{
						$rangeSecond = '2015-04-23';
					}					

				echo '<div class ="well well-lg col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
					<h1>	
					<div class = "text-center">';							
							echo 'Range: ';
							echo $rangeFirst;
							echo '   to   ';
							echo $rangeSecond;
							echo '<br>';						
					echo '</div>
				</h1>
					<h3>
					<table class="table table-striped">
						<tr class="info">
							<td>';											
								echo 'Accounts';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr>
							<td>';											
									echo 'Created: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberOfAccountsCreated($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}								
							echo '</td>
						</tr>
						<tr>
							<td>';											
									echo 'Validated: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberOfAccountsVal($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}								
							echo '</td>
						</tr>						
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Projects';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr>
							<td>';
								echo 'Created: ';
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberOfProjects($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td>
						</tr>							
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Future Notifications';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>				
						<tr>
							<td>';
								echo 'Created: ';
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberOfFutureNotificationsCreated($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td>
						</tr>						
						<tr>
							<td>';
								echo 'Sent: ';
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberOfFutureNotificationsSent($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td>
						</tr>
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Change In Risk Notifications';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>

						<tr>
							<td>';
								echo 'Created: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberChangeInStateNotificationsCreated($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td
						<tr>		
						<tr>
							<td>';
								echo 'Sent: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeNumberNumberChangeInStateNotificationsSent($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td
						</tr>	
						<tr>
							<td>';											
								echo '';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>
						<tr class="info">
							<td>';											
								echo 'Zip Code';								
								echo '</td>
							<td>';
								echo '';															
							echo '</td>
						</tr>

						<tr>
							<td>';
								echo 'Searched By Guest: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeZipGuest($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td
						<tr>		
						<tr>
							<td>';
								echo 'Searched By User: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeZipUser($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td
						</tr>	
						<tr>
							<td>';
								echo 'Searched By Project: ';								
							echo '</td>
							<td>';
								$result = $admindb->getRangeZipProject($rangeFirst, $rangeSecond);
								if($result != NULL){
									echo $result;
								}else{
									echo '0';
								}
							echo '</td
						</tr>											
					</table>
				</h3>
				</div>';
			}
			if(isset($_POST['btn_promote']) || isset($_POST['btn_userPromotion']) ){
				
				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');

				$userdb = new DbUser();

				?>
				<div class="well well-lg col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
						
					<form class="form-horizontal col-sm-12 center" action="/admin.php" method="post">
						<div class="form-group row">
							<label for="tb_email" class="control-label">Email</label>
							<div class="input-group">
								<span class="input-group-addon">*</span>
								<input type="text" class="form-control" name="userEmail" value="<?php echo (isset($_POST['tb_email']) ? $_POST['tb_email'] : "");?>" placeholder="Email Address">
							</div>
						</div>
						<button class="btn btn-primary" name="btn_userPromotion" type="submit">Promote</button>
					</form>
				</div>
				<?php

				if(isset($_POST['btn_userPromotion'])){
					$userID = $userdb->isUser($_POST['userEmail']);
					if($userID != NULL){
						$userAdmin = $userdb->getIsAdmin($userID);
						if($userAdmin == 'y'){
							?><script>$('#alertAlreadyAdmin').show()</script><?php
						}else{
							$userdb->makeAdmin($userID);
							?><script>$('#alertUserPromoted').show()</script><?php
						}
					}else{
						?><script>$('#alertUserDoesNotExist').show()</script><?php
					}
				}
			}
		?>

		<!-- Range Modal -->
		<div class="modal fade" id="dateRange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  			<div class="modal-dialog">
  		  		<div class="modal-content container-fluid">
					<form class="form-horizontal" form action="/admin.php" method="post" >	
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="Range" id="myModalLabel">Date Range</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group">
										<label for="dateFirst" class="control-label">First Date</label>
										<div class="input-group date form_date col-xs-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dateFirst" data-link-format="yyyy-mm-dd">
											<input class="form-control" input type="text" id="dateFirst" name="dateFirst" value="" readonly>
											<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div>

									<div class="form-group">
										<label for="dateSecond" class="control-label">Second Date</label>
										<div class="input-group date form_date col-xs-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dateSecond" data-link-format="yyyy-mm-dd">
											<input class="form-control" input type="text" id="dateSecond" name="dateSecond" value="" readonly>
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
									<button class="btn btn-primary" name="btn_sure" type="submit">Submit</button>
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
			weekStart: 0,
			autoclose: 1,
			startView: 4,
			minView: 2,
			todayBtn: true,
			todayHighlight: true,
		});
		</script>				
	</body>
</html>