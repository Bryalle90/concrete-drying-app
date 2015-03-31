<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DataService.php');
    
    session_start();
    
    $projectdb = new DbProject();
    
    if(isset($_SESSION['id']) && $projectdb->getOwner($_SESSION['activeProject']) == $_SESSION['id']){
        if($_POST['newName'] != '')
            $projectdb->changeProjectName($_SESSION['activeProject'], $_POST['newName']);
        if($_POST['newZip'] != ''){
            
            $dataService = new DataService((int)$_POST['newZip']);
            $city = $dataService->getCity();
            $state = $dataService->getState();
            
            if($city != Null && $state != Null){
                $projectdb->changeProjectLocation($_SESSION['activeProject'], $city.', '.$state);
                $projectdb->changeProjectZip($_SESSION['activeProject'], $_POST['newZip']);
            } else {
                echo 'not able to change zipcode';
            }
        }
    } else {
        echo 'only the owner of the project can edit';
    }
?>