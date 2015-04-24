<?php
	if($_SERVER['DOCUMENT_ROOT']){
		?><script> window.location.replace("/index.php"); </script><?php
		exit();
	}
	$root = '/home/s002457/html';
	require_once($root.'/classes/DbChangeInStateNotification.php');
	require_once($root.'/classes/WeatherService.php');
	require_once($root.'/classes/DataService.php');
	require_once($root.'/classes/DbProject.php');
	require_once($root.'/classes/DbSeries.php');
	require_once($root.'/classes/DbUser.php');
	require_once($root.'/classes/mail.php');
	
	$LOWRISKBOUND = 0.15;
	$MODRISKBOUND = 0.20;
	
	$LOW = 0;
	$MOD = 1;
	$HIGH = 2;
	
	$LOWMOD = 3;
	$MODHIGH = 4;
	$LOWHIGH = 5;

	$riskdb = new DbChangeInStateNotification();
	$projectdb = new DbProject();
	$seriesdb = new DbSeries();
	$userdb = new DbUser();
	$mailer = new Email();
	
	date_default_timezone_set('America/New_York');
	$today = date('Y-m-d', strtotime('now'));
	
	// -- future notifications --
	$projects = $projectdb->getProjectsWithReminders();
	if($projects){
		foreach($projects as $projectID){
			$reminder = $projectdb->getReminder($projectID);
			if($reminder < $today){
				$projectdb->changeReminder($projectID, "");
			}
			elseif($reminder == $today){
				sendReminderEmail($projectID);
			}
		}
	}
	
	// -- risk notifications --
	$notifications = $riskdb->getAllNotif();
	if($notifications){
		foreach($notifications as $nID => $notification){
			$zip = $projectdb->getZipcode($notification['projectID']);
			$unit = $projectdb->getUnit($notification['projectID']);
			$cTemp = $seriesdb->getConcreteTemp($notification['seriesID']);
			$isIndoors = $seriesdb->getWindSpeed($notification['seriesID']);
			
			$dataService = new DataService($zip);
			
			$lat = $dataService->getLat();
			$lon = $dataService->getLon();
			
			$weatherService = new WeatherService($zip, $lat, $lon);
			$weatherService->getWeatherData();
			
			$evapRates = $weatherService->getHourlyEvap();
			
			if(array_key_exists($notification['time'], $evapRates)){
				$newRisk = $evapRates[$notification['time']];
				if($newRisk){
					if($cTemp != 0 or $isIndoors == 1){
						$concTemps = $weatherService->getHourlyConcTemp();
						$wSpeeds = $weatherService->getHourlyWindSpeed();
						$airTemps = $weatherService->getHourlyAirTemp();
						$humidities = $weatherService->getHourlyHumidity();
						
						$concTemp = $cTemp ? $cTemp : $concTemps[$notification['time']];
						$windspeed = $isIndoors ? $isIndoors : $wSpeeds[$notification['time']] ;
						$airTemp = $airTemps[$notification['time']];
						$humidity = $humidities[$notification['time']];
						$newRisk = $weatherService->calcEvap($unit == 'S' ? $concTemp : $weatherService->convertCtoF($concTemp), $humidity, $airTemp, $windspeed);
					}
					
					// see what risk the evap rate is at
					if($newRisk <= $LOWRISKBOUND)
						$newRisk = $LOW;
					elseif($newRisk <= $MODRISKBOUND)
						$newRisk = $MOD;
					else
						$newRisk = $HIGH;
					
					$oldRisk = $notification['currentZone'];
					
					$users = $projectdb->getUsersOfProject($notification['projectID']);
					if($notification['notifyZone'] == $newRisk){
						sendRiskEmail($notification['projectID'], $nID, $notification['time'], $oldRisk, $newRisk);
					}
					elseif($notification['notifyZone'] == $LOWMOD && ($newRisk == $LOW || $newRisk == $MOD)){
						sendRiskEmail($notification['projectID'], $nID, $notification['time'], $oldRisk, $newRisk);
					}
					elseif($notification['notifyZone'] == $MODHIGH && ($newRisk == $MOD || $newRisk == $HIGH)){
						sendRiskEmail($notification['projectID'], $nID, $notification['time'], $oldRisk, $newRisk);
					}
					elseif($notification['notifyZone'] == $LOWHIGH && ($newRisk == $LOW || $newRisk == $HIGH)){
						sendRiskEmail($notification['projectID'], $nID, $notification['time'], $oldRisk, $newRisk);
					}
				}
			}
		}
	}
	
	function getRiskLevel($risk){
		$level = NULL;
		switch($risk){
			case 0:
				$level = 'low';
				break;
			case 1:
				$level = 'moderate';
				break;
			case 2:
				$level = 'high';
				break;
		}
		return $level;
	}
	
	function sendReminderEmail($projectID){
		global $projectdb, $userdb, $mailer;
		$projectName = $projectdb->getName($projectID);
		$zip = $projectdb->getZipcode($projectID);
		
		$users = $projectdb->getUsersOfProject($projectID);
		foreach($users as $userID){
			$email = $userdb->getEmail($userID);
			$projectdb->changeReminder($projectID, "");
			$mailer->futureNotif($email, $projectName, $zip);
		}
	}
	
	function sendRiskEmail($projectID, $notificationID, $time, $oldRisk, $newRisk){
		global $projectdb, $userdb, $riskdb, $mailer;
		$oldRisk = getRiskLevel($oldRisk);
		$newRisk = getRiskLevel($newRisk);
		$projectName = $projectdb->getName($projectID);
		$zip = $projectdb->getZipcode($projectID);
		$users = $projectdb->getUsersOfProject($projectID);
		foreach($users as $userID){
			$email = $userdb->getEmail($userID);
			$riskdb->deleteNotification($notificationID);
			$mailer->changeInRiskNotif($email, $projectName, $zip, $time, $oldRisk, $newRisk);
		}
	}
?>