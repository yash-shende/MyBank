<?php

header('Content-Type: application/json');
header('Acess-Control-Allow-Origin: *');
header('Acess-Control-Allow-Method: POST');
header('Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method,Authorization,X-Requested-With');

$data = json_decode(file_get_contents("php://input"),true);
try{
$customer_fname= $data['firstname'] or die("Enter First Name");
$customer_lname= $data['lastname'] or die("Enter last name");
$customer_email= $data['email'] or die("Enter email");
$customer_accno= $data['accno'] or die("Enter Account Number");
$customer_pass= base64_encode($data['password'])or die("Enter Password");
$customer_gender=$data['gender'] or die("Enter date");
$customer_dob=$data['dob']or die("Enter DOB");
$customer_country=$data['country']or die("Enter Country");
}
catch(Error  $e){
    echo $e->getMessage();
}

if($customer_pass=="" || $customer_accno=="" || $customer_lname=="" ||$customer_fname=="" || $customer_dob=="" || $customer_country="" || $customer_gender=="" ) http_response_code(400);
if (!preg_match('/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/',$customer_email))die('invalid email Format');

// connection setup 
include '../connection.php'; 

if($_SERVER["REQUEST_METHOD"]=="POST"){ 

$query = "INSERT INTO dtbase(firstname,lastname,email,psword,dob,country,gender,acNo,status1) VALUES ('$customer_fname','$customer_lname', '$customer_email','$customer_pass','$customer_dob','$customer_country','$customer_gender','$customer_accno','allowed')";
$result = mysqli_query($conn,$query) or die("Sql query failed !!");



if($result){    
    $user_arr=array(        
        "Message" => "Successfully Registered!!",
        "Status" => true,
     );
    http_response_code(201);
    echo json_encode($user_arr);
}
else{
    http_response_code(400);
    echo json_encode(array('Message'=>'Bad Request','Status '=>'False'));
}
}
else{
    http_response_code(400);
    echo json_encode(array("Status"=>"Bad Request"));
}

//Testing INPUT's FOR Postman
// {
//     "firstname": "Amit",
//     "lastname": "Thakre",
//     "email": "amit@gmail.com",
//     "accno": 123456789,
//     "password": "pass",
//     "gender": "male",
//     "dob": "1992-06-23",
//     "country": "india"
// } 

?>

