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
	</head>

	<body style="background-color: #DBDBDB">		
		<div class="container-fluid">
			<div class="container-fluid">			
			<?php
				session_start();

				// send user to index if not admin
				if(!$_SESSION['admin'])
					header("Location: /index.php");			

				
				include($_SERVER['DOCUMENT_ROOT'].'/classes/DbAdmin.php');
					
				$admindb = new DbAdmin();
			?>

			<h1>	
				<div class = "text-center">	
					Total Stats
					<br>
				
				</div>
			</h1>

			
			<h3>	
				<table class="table table-striped">
					<tr>
						<td>
							<?php				
								echo 'Total Accounts: ';
							?>
						</td>
						<td>
							<?php				
								$result = $admindb->getTotalNumberOfAccounts();
									if($result != NULL){
										echo mysql_result($result, 0);
									}else{
										echo '0';
									}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php				
								echo 'Total Projects: ';
							?>
						</td>
						<td>
							<?php
								$result = $admindb->getTotalNumberOfProjects();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php				
								echo 'Total Shared Projects: ';
							?>
						</td>
						<td>
							<?php
								$result = $admindb->getTotalNumberOfSharedProjects();
								if($result != NULL){
								echo $result;
								}else{
									echo '0';
								}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo 'Total Current Future Notifications: ';
							?>
						</td>
						<td>
							<?php
								$result = $admindb->getTotalNumberOfFutureNotifications();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo 'Total Change In Risk Notifications: ';
							?>
						</td>
						<td>
							<?php
								$result = $admindb->getTotalNumberOfChangeInStateNotifications();
								if($result != NULL){
									echo mysql_result($result, 0);
								}else{
									echo '0';
								}
							?>
						</td
					<tr>
				</table>
			</h3>
			<?php
				include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
			?>
			</div>
		</div>
	</body>
</html>