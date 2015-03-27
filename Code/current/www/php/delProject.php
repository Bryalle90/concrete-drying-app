<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
    session_start();
    
    if(isset($_POST['projectID'])){
        $projectdb = new DbProject();
        $projectdb->deleteProject($_POST['projectID'], $_SESSION['id']);
    }
?>