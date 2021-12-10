<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json');
header('Access-Control-Allow-Methods:GET');
header('Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Request-With');


$data = json_decode(file_get_contents("php://input"),true);

$account_number  = $data['acc_number'];

if($account_number==NULL) die('Bad Request!');

include '../connection.php'; 

if($_SERVER["REQUEST_METHOD"]=="GET"){

$query = "SELECT status1 FROM atmrequest WHERE (remacno = $account_number)";

$result = mysqli_query($conn,$query) or die("Query failed");

if(mysqli_num_rows($result)>0){
    
    while($row=mysqli_fetch_assoc($result))
    {
        $atm_card_status=$row["status1"];
    }
    http_response_code(200);
    echo json_encode(array("Account Number"=>$account_number,"Card Status"=>$atm_card_status,"Status"=>"True"));
}
else{
    http_response_code(204);
    echo json_encode(array('Message'=>'account doesn\'t Exist','Status '=>'False'));
}
}
else{
    http_response_code(400);
    echo json_encode(array("Status"=>"Bad Request"));
}
?>