<?php 
header('Content-Type: application/json');
header('Acess-Control-Allow-Origin: *');
header('Acess-Control-Allow-Method: POST');
header('Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method,Authorization,X-Requested-With');

$data = json_decode(file_get_contents("php://input"),true);


$customer_email  = $data['email'];
if($customer_email=="") die('mail invalid');

// connection setup 
include '../connection.php'; 

if($_SERVER["REQUEST_METHOD"]=="GET"){

$query = "SELECT id,firstname,lastname,country FROM dtbase WHERE (email = '$customer_email')";

$result = mysqli_query($conn,$query) or die("query failed");


if(mysqli_num_rows($result)>0){
    $output = mysqli_fetch_all($result,MYSQLI_ASSOC);
    http_response_code(200);
    echo json_encode($output);
}
else{
    http_response_code(400);
    echo json_encode(array('Message'=>'No record found ','Status '=>'False'));
}
}
else{
    http_response_code(400);
    echo json_encode(array("Status"=>"Bad Request"));
}
?>