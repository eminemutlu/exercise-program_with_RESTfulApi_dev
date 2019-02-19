<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/plans.php';
 
$database = new Database();
$db = $database->getConnection();
 
$plan = new Plans($db);
 
$plan->plan_id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $plan->read_plan_detail();
$num = $stmt->rowCount();

if($num>0){
 
    $plans_arr=array();
    $plans_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $plan_item=array(
            "id" => $id,
            "plan_id" => $plan_id,
            "body_part_title" => $body_part_title,
            "exercise_id" => $exercise_id,
            "plan_name" => $plan_name,
            "user_ids" => $user_ids
        );

        array_push($plans_arr["records"], $plan_item);
    }
 
  
    http_response_code(200);
 
    echo json_encode($plans_arr);
}
 

else{
 
    http_response_code(404);

    echo json_encode(
        array("message" => "No plan found.")
    );
}



?>