<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/plans.php';
 
$database = new Database();
$db = $database->getConnection();
 
$plan = new Plans($db);
 
$stmt = $plan->all_plans();
$num = $stmt->rowCount();
 
if($num>0){
 
    $plans_arr=array();
    $plans_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $plans_item=array(
            "id" => $id,
            "plan_id" => $plan_id,
            "plan_name" => $plan_name,
            "user_ids" => $user_ids,
            "value" => $value
        );

        array_push($plans_arr["records"], $plans_item);
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