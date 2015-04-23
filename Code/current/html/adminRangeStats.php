<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Admin</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="bootstrap/css/theme.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	</head>

	<body style="background-color: #DBDBDB">		
		<div class="container-fluid">		

			<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-3 col-xs-12 col-sm-8 col-md-6">

			<?php
				session_start();

				// send user to index if not admin
				if(!$_SESSION['admin'])
					header("Location: /index.php");				
				
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

				?>	

				<h1>	
					<div class = "text-center">		
						<?php
							echo 'Range: ';
							echo $rangeFirst;
							echo '   to   ';
							echo $rangeSecond;
							echo '<br>';
						?>
					</div>
				</h1>
					<h3>
					<table class="table table-striped">
					<tr>
						<td>
							<?php
								echo 'Number of Accounts: ';
							?>
						</td>
						<td>
							<?php
								$admindb->getRangeNumberOfAccounts($rangeFirst, $rangeSecond);
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php	
								echo 'Number of Projects: ';
							?>
						</td>
						<td>
							<?php					
								$admindb->getRangeNumberOfProjects($rangeFirst, $rangeSecond);
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php					
								echo 'Number of Future Notifications: ';
							?>
						</td>
						<td>
							<?php
								$admindb->getRangeNumberOfFutureNotifications($rangeFirst, $rangeSecond);
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo 'Number of Change in Risk Notifications: ';
							?>
						</td>
						<td>
							<?php
								$admindb->getRangeNumberOfChangeInStateNotifications($rangeFirst, $rangeSecond);
							?>
						</td>
					</tr>
				</table>
				</h3>
			<?php			
				include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
			?>
			</div>
		</div>
	</body>
</html>