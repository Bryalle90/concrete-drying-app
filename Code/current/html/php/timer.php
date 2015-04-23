<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbChangeInStateNotification.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/WeatherService.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DataService.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbUser.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/mail.php');
	
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
		foreach($projects as $pID){
			$reminder = $projectdb->getReminder($pID);
			if($reminder == $today){
				$projectName = $projectdb->getName($pID);
				$zip = $projectdb->getZipcode($pID);
				
				$users = $projectdb->getUsersOfProject($pID);
				foreach($users as $userID){
					$email = $userdb->getEmail($userID);
					// $mailer->FutureNotif($email, $projectName, $zip, $reminder);
				}
			}
		}
	}
	
	// -- risk notifications --
	$notifications = $riskdb->getAllNotif();
	if($notifications){
		foreach($notifications as $nID => $notification){
			$zip = $projectdb->getZipcode($notification['projectID']);
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
					if($cTemp or $isIndoors){
						$concTemp = $cTemp ? $cTemp : $weatherService->getHourlyConcTemp()[$notification['time']];
						$windspeed = $isIndoors ? $isIndoors : $weatherService->getHourlyWindSpeed()[$notification['time']] ;
						$airTemp = $weatherService->getHourlyAirTemp()[$notification['time']];
						$humidity = $weatherService->getHourlyHumidity()[$notification['time']];
						$newRisk = $weatherService->calcEvap($concTemp, $humidity, $airTemp, $windspeed);
					}
					
					// see what risk the evap rate is at
					if($newRisk <= $LOWRISKBOUND)
						$newRisk = $LOW;
					elseif($newRisk <= $MODRISKBOUND)
						$newRisk = $MOD;
					else
						$newRisk = $HIGH;
					
					$oldRisk = $notification['currentZone'];
					
					echo $notification['notifyZone'];
					echo $newRisk.'<br>';
					
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
			//$mailer->ChangeInRiskNotif($email, $projectName, $zip, $time, $oldRisk, $newRisk);
		}
	}
?>