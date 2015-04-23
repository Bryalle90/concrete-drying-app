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
				<div class="btn-group">					
					<a class="btn btn-primary btn-xl" type="button" data-toggle="tooltip" data-placement="bottom" title="View Total Stats" href="/adminTotalStats.php" data-content="You can view total stats here">Total Stats</a>
					<a class="btn btn-primary btn-xl" type="button" data-toggle="modal" data-placement="bottom" title="View Range of Stats" data-target="#dateRange"data-content="You can set a range of time and view stats in that range here">Ranged Stats</a>
					<a class="btn btn-primary btn-xl" type="button" data-toggle="tooltip" data-placement="bottom" title="Reset System" href="/adminReset.php" data-content="Last resort to fix system if broke. Use as last resort. NOT REVERSEABLE">System Reset</a>
				</div>
			</div>
		</div>	

		<!-- Modal -->
		<div class="modal fade" id="dateRange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  			<div class="modal-dialog">
  		  		<div class="modal-content">
  		    			<div class="modal-header">
     		   				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		  				<h4 class="Range" id="myModalLabel">Date Range</h4>
     		 			</div>
					<form class="form-horizontal" form action="/adminRangeStats.php" method="post" >	
      						<div class="modal-body">
							<div class="col-xs-12">
      		 						<div class="form-group">
									<label for="dateFirst" class="control-label">First Date</label>
									<div class="input-group date form_date col-xs-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dateFirst" data-link-format="yyyy-mm-dd">
										<input class="form-control" input type="text" id= "dateFirst" name="dateFirst" value="" readonly/>
										<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div>
								<script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
								<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
								<script>
								$('.form_date').datetimepicker({
									language:  'en',
									startDate: '2015-01-01',
									weekStart: 0,
									autoclose: 1,
									startView: 3,
									minView: 2,												
								});
								</script>

								<div class="form-group">
									<label for="dateSecond" class="control-label">Second Date</label>
									<div class="input-group date form_date col-xs-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dateSecond" data-link-format="yyyy-mm-dd">
										<input class="form-control" input type="text" id="dateSecond" name="dateSecond" value="" readonly/>
										<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div>
								<script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
								<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
								<script>
								$('.form_date').datetimepicker({
									language:  'en',
									startDate: '2015-01-01',
									weekStart: 0,
									autoclose: 1,
									startView: 3,
									minView: 2,
								});
								</script>	 
     							</div>
					
     							<div class="modal-footer">
      		  						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		 						<input type="submit" class="btn btn-default" Close />
						</div>
					</form>
      				</div>
    			</div>
  		</div>
	</body>
</html>