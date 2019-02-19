<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/plans.php';

$database = new Database();
$db = $database->getConnection();

$plan = new Plans($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->plan_id) &&
    !empty($data->body_part_title) &&
    !empty($data->exercise_id)
){
 
   
    $plan->plan_id = $data->plan_id;
    $plan->sort_order = $data->sort_order;
    $plan->body_part_title = $data->body_part_title;
    $plan->exercise_id = $data->exercise_id;
    
    if($plan->createdays()){
        
        http_response_code(201);
 
        echo json_encode(array("message" => "OK"));
    }
    else{
 
        http_response_code(503);
 
        echo json_encode(array("message" => "Unable to create plan."));
    }
}
 
else{
 
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to create plan. Data is incomplete."));
}


?>