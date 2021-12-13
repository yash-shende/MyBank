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
        $query = "select * from loan_applications";
        $result = mysqli_query($conn,$query) or die("query failed");
        
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $records[]=$row;
            }   
            echo json_encode($records);
        }
    }
    function readUser($Received_id,$conn){
        $query = "select * from loan_applications where id=".$Received_id;
        $result = mysqli_query($conn,$query) or die("query failed");

        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                echo json_encode($row);
            }
            

        }
        else{
            http_response_code(200);
            echo json_encode(array("message"=>"No Application Found"));
        }
    }
    //GET USER Method for Read operations ============GET USER =====================GET USER =============GET USER ====================


    //CREATE USER ===================CREATE USER===========================CREATE USER====================================CREATE USER===========================
    function doPost($conn,$data){
        $app_name  = isset($data['name'])?$data['name']:null;
        $app_age = isset($data['age'])?$data['age']:null;
        $app_email = isset($data['email'])?$data['email']:null;
        $app_cred_score = isset($data['credScore'])?$data['credScore']:null;

        if($app_name==null || $app_age==null || $app_email==null || $app_cred_score==null){
            status_message(400,"Unable to create new Application. Invalid data / Missing Parameters");
            die;
        }
        if (!preg_match('/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/',$app_email)){
            http_response_code(400);            
            echo json_encode(array("Message"=>"Invalid Email ID"));
            die;
        }
        $queryDuplicateRecord = "select * from loan_applications where email = '".$app_email."'";
        $resultDuplicateRecord = mysqli_query($conn,$queryDuplicateRecord)or die("query failed");
        if(mysqli_num_rows($resultDuplicateRecord)>0){
            status_message(400,"Application Request with this Email id Already Exist");
        die;
        }
        
        $query = "INSERT INTO loan_applications(name,age,email,cred_score) VALUES ('$app_name',$app_age, '$app_email', $app_cred_score)";
        $result = mysqli_query($conn,$query) or die("Sql query failed !!");

        status_message(201,"Application Submitted Successfully!");

        // {
        //     "name": "Ashish Sawant",
        //     "age": 25,
        //     "email": "ashish@gmail.com",
        //     "credScore": 650
        // }

    }
    //CREATE USER ===================CREATE USER===========================CREATE USER====================================CREATE USER===========================

    //UPDATE USER ====================UPDATE USER==========================UPDATE USER=================
    function doPut($conn,$data){
    $app_email = isset($data['email'])?$data['email']:null;
    $new_credScore = isset($data['newCredSocre'])?$data['newCredSocre']:null;

    if($app_email==null || $new_credScore==null){
        status_message(400,"Unable to Update Application. Invalid data / Missing Parameters");
        die;
    }

    $queryDuplicateRecord = "select * from loan_applications where email = '".$app_email."'";
    $resultDuplicateRecord = mysqli_query($conn,$queryDuplicateRecord)or die("query failed");
    if(mysqli_num_rows($resultDuplicateRecord)==0){
        status_message(400,"Application Request with this Email id Doesn't Exist");
        die;
    }

    

    if($new_credScore>1000 || $new_credScore<100){
        status_message(400,"Invalid Credit Score");
        die;
    }
    else{
        $query = "update loan_applications set cred_score='".$new_credScore."' where email='".$app_email."'";
        $result = mysqli_query($conn,$query)or die("query failed");
        status_message(200,"Credit Score Updated successfully");
    }

    // {
    //     "email": "anil@gmail.com",
    //     "newCredSocre": 700
    // }

}
    //UPDATE USER ====================UPDATE USER======


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
        $query = "select * from loan_applications where id=".$id;        
        $result = mysqli_query($conn,$query) or die("query failed");
        if(mysqli_num_rows($result)>0){
            $delQuery = "DELETE FROM loan_applications WHERE id =".$id;
            mysqli_query($conn,$delQuery) or die("query failed");
            http_response_code(200);
            echo json_encode(array("Message"=>"Application Request Deleted","Status"=>"true"));
        }
        else{
            http_response_code(503);
            echo json_encode(array("Message "=>"Id doesn't Exits or Apllication Request Already Deleted","status"=>"False"));
        }
        
    }

//DELETE USER Method ========DELETE USER===========================DELETE USER==================DELETE USER===========================

?>