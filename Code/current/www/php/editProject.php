<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DataService.php');
    
    session_start();
    
    $projectdb = new DbProject();
    
    if(isset($_SESSION['id']) && $projectdb->getOwner($_POST['projectID']) == $_SESSION['id']){
        if($_POST['newName'] != '')
            $projectdb->changeProjectName($_POST['projectID'], $_POST['newName']);
        if($_POST['newZip'] != ''){
            
            $dataService = new DataService((int)$_POST['newZip']);
            $city = $dataService->getCity();
            $state = $dataService->getState();
            
            if($city != Null && $state != Null){
                $projectdb->changeProjectLocation($_POST['projectID'], $city.', '.$state);
                $projectdb->changeProjectZip($_POST['projectID'], $_POST['newZip']);
            } else {
                echo 'not able to change zipcode';
            }
        }
    } else {
        echo 'only the owner of the project can edit';
    }
?>