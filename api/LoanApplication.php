<?php

header('Content-Type: application/json');
header('Acess-Control-Allow-Origin: *');
header('Acess-Control-Allow-Method: POST');
header('Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method,Authorization,X-Requested-With');

$data = json_decode(file_get_contents("php://input"),true);

$app_name  = $data['aname'];
$app_age = $data['aage'];
$app_email = $data['aemail'];
$app_cred_score = $data['acred'];

// connection setup 
include '../connection.php'; 

if($_SERVER["REQUEST_METHOD"]=="POST"){ 

$query = "INSERT INTO loan_applications(name,age,email,cred_score) VALUES ('$app_name',$app_age, '$app_email', $app_cred_score)";
$result = mysqli_query($conn,$query) or die("Sql query failed !!");

if($app_cred_score>900)
    $output =array("Amount"=>"250000","Processing_fee"=>"25000","Intrest"=>"8.4%","Branch"=>"Nagpur");
else if($app_cred_score<500)
    $output =array("Amount"=>"NA","Comment"=>"Not Elegible","Reason"=>"poor credit score");
else
    $output =array("Amount"=>"500000","Processing_fee"=>"2500","Intrest"=>"11.2%","Branch"=>"Nagpur");

http_response_code(200);
echo json_encode($output);
}
else{
    http_response_code(400);
    echo json_encode(array("Status"=>"Bad Request"));
}
?>