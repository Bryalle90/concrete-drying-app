<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
    session_start();
    
    $projectdb = new DbProject();
    if($projectdb->isUserInProject($_POST['projectID'], $_SESSION['id'])){
        $projectdb->deleteProject($_POST['projectID'], $_SESSION['id']);
    }
?>