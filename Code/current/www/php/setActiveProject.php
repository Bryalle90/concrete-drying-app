<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
	session_start();
    
    if(isset($_SESSION['id'])){
        $projectdb = new DbProject();
        if($projectdb->isUserInProject($_POST['pID'], $_SESSION['id'])){
            $_SESSION['activeProject'] = $_POST['pID'];
            echo $_POST['pID'];
        }
    }
?>