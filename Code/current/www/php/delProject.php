<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
    session_start();
    
    $projectdb = new DbProject();
    if($projectdb->isUserInProject($_SESSION['activeProject'], $_SESSION['id'])){
        $projectdb->deleteProject($_SESSION['activeProject'], $_SESSION['id']);
    }
?>