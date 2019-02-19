<?php
	
	include_once '../config/database.php';
	include_once '../objects/plans.php';

	$database = new Database();
	$db = $database->getConnection();

	$getid = new Plans($db);

	$num = $getid->max_get_id();
	$num2=0;

	if(isset($num)) {
		echo $num;
	} else {
		echo $num2;
	}

	
	

?>