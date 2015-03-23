<!DOCTYPE html>
<html>
<body>

<?php
include 'user.php';
include 'project.php';

//$testObject = new User();
//$testObject->connectdb();

$testObject = new Project();
$testObject->connectdb();

//$testObject->addToProjectTable('project1', 1, 62069, 'PST');
//$testObject->addToProjectTable('project2', 2, 90001, 'CST');
//$testObject->addToProjectTable('project3', 3, 62025, 'PMT');

//$testObject->addUserToSharedProject(14, 1);

$testObject->deleteProject(14, 3);

//$testObject->changeProjectName(10, "NewName");

//$result = $testObject->getUserProjects(1);
//$index = 0;		
//while($row = mysql_fetch_assoc($result)) {
//	echo mysql_result($result, $index);
//	$index++;
//}


//echo $testObject->getName(10);
//echo $testObject->getZipcode(10);
//echo $testObject->getTimeZone(10);


//$testObject->addUser('Zach', 0,'zachsmi@siue.edu', 'abc', 'y');
//$testObject->addUser('Dave', 0,'dudedave@siue.edu', '123', 'n');
//$testObject->addUser('Cara', 0,'caracase@siue.edu', '000', 'n');

//$testObject->deleteUser(3);

//$testObject->changeName(2, 'David');

//$testObject->changeEmail(2, 'dudedavid@siue.edu');

//$testObject->changePassword(2, '1234');

//if($testObject->isUserAdmin(1) == TRUE){
//	echo 'True';
//}else{
//	echo 'False';
//}

//if($testObject->isUserAdmin(2) == TRUE){
//	echo 'True';
//}else{
//	echo 'False';
//}

//echo $testObject->getName(1);
//echo $testObject->getCurrentNumberOfNotifications(1);
//echo $testObject->getEmail(1);
//echo $testObject->getUserPass(1);
//echo $testObject->getIsAdmin(1);

//echo $testObject->isUser('zachsmi@siue.edu', 'abc');


?>

</body>
</html>