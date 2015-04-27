<?php

require_once $_SERVER['DOCUMENT_ROOT']."/classes/mail.php";

$e = new Email;

$e->EmailGraph($_POST['emailAdd'], $_POST['emailURL'], $_POST['zip'], $_POST['userName'], $_POST['msg']);

echo 'success';

?>
