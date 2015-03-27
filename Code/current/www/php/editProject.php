<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DataService.php');
    
    session_start();
    
    if(isset($_POST['projectID'])){
        $projectdb = new DbProject();
        if($_POST['newName'] != '')
            $projectdb->changeProjectName($_POST['projectID'], $_POST['newName']);
        if($_POST['newZip'] != ''){
            
            $dataService = new DataService((int)$_POST['newZip']);
            $city = $dataService->getCity();
            $state = $dataService->getState();
            
            if($city != Null && $state != Null){
                $projectdb->changeProjectZip($_POST['projectID'], $_POST['newZip']);
            } else {
                echo 'not able to change zipcode';
            }
        }
    }
?>