<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DataService.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
	session_start();
    
    if(isset($_POST['btn_add'])){
        $dataService = new DataService((int)$_POST['zip']);
        $city = $dataService->getCity();
        $state = $dataService->getState();
        
        if($city != Null && $state != Null){
            $location = $city.', '.$state;
            $title = $_POST['nameInput'] == '' ? $location : $_POST['nameInput'];
            $projectdb = new DbProject();
            $projectdb->addToProjectTable($title, $location, $_SESSION['id'], (int)$_POST['zip'], date('Y-m-d H:i:s', strtotime('now')), $_POST['unit']);
        }
    }
    
    header ("Location: /../projects.php");

?>