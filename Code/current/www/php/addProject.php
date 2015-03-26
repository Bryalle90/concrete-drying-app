<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DataService.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/Project.php');
    
	session_start();
    
    $dataService = new DataService((int)$_POST['zip']);
    $city = $dataService->getCity();
    $state = $dataService->getState();
    
    $title = $city.', '.$state;
    
    $projectdb = new Project();
    $projectdb->connectdb();
    $projectdb->addToProjectTable($title, $_SESSION['id'], (int)$_POST['zip'], $_POST['unit']);
    
    header ("Location: /../projects.php");

?>