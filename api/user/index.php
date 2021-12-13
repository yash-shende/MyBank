<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE,PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../connection.php'; 
include("../config/Auth.php");
include("../config/status_message.php");

$data = json_decode(file_get_contents("php://input"),true);

$method = $_SERVER['REQUEST_METHOD'];

    if($method=='DELETE' || $method=='POST' || $method=='GET' || $method=='PUT'){

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $email = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            if(!Check_admin_access($email,$pass)){
                status_message(403,"Invalid Admin Credentials");
            }
        }else{
                status_message(403,"UnAuthorised User");
        }
    }
    else{
        status_message(405,"Invalid Method Call on URL.");
    }


$reqMethod =  $_SERVER['REQUEST_METHOD'];

if($reqMethod=='PUT'){
    // echo "PUT";
    doPut($conn,$data);
}
else if($reqMethod=='GET'){
    // echo "GET";
    doGet($conn);    
}
else if($reqMethod == 'POST'){
    doPost($conn,$data);
    // echo "POST";
}
else if($reqMethod == 'DELETE'){
    // echo "DELETE";
    doDelete($conn);
}
else{
    http_response_code(405);
}

//GET USER Method for Read operations ============GET USER =====================GET USER =============GET USER ====================
function doGet($conn){
    
    $Received_id = isset($_GET['id']) ? $_GET['id'] :null;
    if($Received_id==null){
        readAllUser($conn);
    }
    else{
        readUser($Received_id,$conn);
    }    
}

function readAllUser($conn){
    $query = "select id,firstname,email,acNo,gender,dob,country from dtbase ";
    $result = mysqli_query($conn,$query) or die("query failed");

    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            echo json_encode($row);
        }
    }
}

function readUser($id,$conn){
    $query = "select id,firstname,email,acNo,gender,dob,country from dtbase where id=".$id;
    $result = mysqli_query($conn,$query) or die("query failed");

    if(mysqli_num_rows($result)>0){

        while($row = mysqli_fetch_assoc($result)){
            echo json_encode($row);
        }

    }
    else{
        http_response_code(200);
        echo json_encode(array("message"=>"No User Found"));
    }
}

//GET USER  over ====================GET USER ======================GET USER ========================GET USER =====================



//DELETE USER Method ========DELETE USER===========================DELETE USER==================DELETE USER===========================
function doDelete($conn){
    $Received_id = isset($_GET['id']) ? $_GET['id'] :null;
    if($Received_id==null){
        echo json_encode(array("Message "=>"ID is Required"));
    }
    else{
        delUser($Received_id,$conn);
    }
}

function delUser($id,$conn){
    $query = "select * from dtbase where id=".$id;        
    $result = mysqli_query($conn,$query) or die("query failed");
    if(mysqli_num_rows($result)>0){
        $delQuery = "DELETE FROM dtbase WHERE id =".$id;
        mysqli_query($conn,$delQuery) or die("query failed");
        http_response_code(200);
        echo json_encode(array("Message"=>"User Deleted ","Status"=>"true"));
    }
    else{
        http_response_code(503);
        echo json_encode(array("Message "=>"Id doesn't Exits or User Already Deleted","status"=>"False"));
    }
    
}
//DELETE USER  =======================DELETE USER ===========================DELETE USER ===============DELETE USER ===============


//CREATE USER ===================CREATE USER===========================CREATE USER====================================CREATE USER===========================
function doPost($conn,$data){

    try{
        $customer_fname= isset($data['firstname'])?$data['firstname']:null;
        $customer_lname= isset($data['lastname'])?$data['lastname']:null;
        $customer_email= isset($data['email'] )?$data['email'] :null;
        $customer_accno=isset( $data['accno'] )? $data['accno'] :null;
        $customer_pass= isset($data['password'])?base64_encode($data['password']):null;
        $customer_gender=isset($data['gender'] )?$data['gender'] :null;
        $customer_dob=isset($data['dob'])?$data['dob']:null;
        $customer_country= isset($data['country'])?$data['country']:null;
    }
    catch(Error  $e){
         echo $e->getMessage();
    }
        
    if($customer_pass==null || $customer_accno=="" || $customer_lname=="" ||$customer_fname=="" || $customer_dob=="" || $customer_country=null || $customer_gender=="" ) {
        http_response_code(400);
        echo json_encode(array("Message"=>"Unable to create User. Invalid data / Missing Parameters"));
        die;
    }
        if (!preg_match('/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/',$customer_email)){
            http_response_code(400);            
            echo json_encode(array("Message"=>"Invalid Email ID"));
            die;
        }

    $queryDuplicateRecord = "select * from dtbase where email = '".$customer_email."'";
    $resultDuplicateRecord = mysqli_query($conn,$queryDuplicateRecord)or die("query failed");
    if(mysqli_num_rows($resultDuplicateRecord)>0){
        status_message(400,"Email id Already Exist");
        die;
    }    

    $query = "INSERT INTO dtbase(firstname,lastname,email,psword,dob,country,gender,acNo,status1) VALUES ('$customer_fname','$customer_lname', '$customer_email','$customer_pass','$customer_dob','$customer_country','$customer_gender','$customer_accno','allowed')";
    $result = mysqli_query($conn,$query) or die("invalid Query");


    if(mysqli_affected_rows($conn)==1){    
        http_response_code(201);//status code for created 
        echo json_encode(array("Message" => "Successfully Registered","Status" => true));
    }
    else{
        http_response_code(400);
        echo json_encode(array('Message'=>'Bad Request','Status '=>'False'));
}

// {
//     "firstname": "Salman",
//     "lastname": "Khan",
//     "email": "Bhaiji@gmail.com",
//     "accno": 32432354232,
//     "password": "1234",
//     "gender": "male",
//     "dob": "1982-06-23",
//     "country": "india"
// } 

}
//CREATE USER ===================CREATE USER===========================CREATE USER====================================CREATE USER===========================

//UPDATE USER ====================UPDATE USER==========================UPDATE USER=================

function doPut($conn,$data){
    
    $email = isset($data['email'])?$data['email']:null;
    $password= isset($data['newPass'])?$data['newPass']:null;
    $confirm_password= isset($data['rePass'])?$data['rePass']:null;

    if($password==null || $confirm_password==null || $email==null){
        http_response_code(400);
        echo json_encode(array("Message"=>"Unable to update password. Invalid data / Missing Parameters"));
        die;
    }

    $queryDuplicateRecord = "select * from dtbase where email = '".$email."'";
    $resultDuplicateRecord = mysqli_query($conn,$queryDuplicateRecord)or die("query failed");
    if(mysqli_num_rows($resultDuplicateRecord)==0){
        status_message(400,"Email id does not Exist");
        die;
    }

   
    $password = base64_encode($password);
    $confirm_password = base64_encode($confirm_password);
    if($password!=$confirm_password){
        status_message(400,"re-pass does not match with password");
        die;
    }
    else{
        $query = "update dtbase set psword='".$password."' where email='".$email."'";
        $result = mysqli_query($conn,$query)or die("query failed");
        status_message(200,"Password Updated successfully");
    }
}
// {    
//    "email": "Bhai@gmail.com",
//     "newPass": "pass123",
//     "rePass": "pass123"
// }

//UPDATE USER ====================UPDATE USER==========================UPDATE USER=================
?>