<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/plans.php';
 
$database = new Database();
$db = $database->getConnection();
 
$exercise = new Plans($db);
 
$stmt = $exercise->read_exercise();
$num = $stmt->rowCount();
 
if($num>0){
 
    $exercise_arr=array();
    $exercise_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $exercise_item=array(
            "id" => $id,
            "title" => $title,
            "status" => $status
        );

        array_push($exercise_arr["records"], $exercise_item);
    }
 
  
    http_response_code(200);
 
    echo json_encode($exercise_arr);
}
 

else{
 
    http_response_code(404);

    echo json_encode(
        array("message" => "No Exercise found.")
    );
}



?>