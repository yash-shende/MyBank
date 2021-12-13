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

    if($method=='DELETE' || $method=='POST' || $method=='GET' || $method=='PATCH'){

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

    if($reqMethod=='PATCH'){
        // echo "PUT";
        doPatch($conn,$data);
    }
    else if($reqMethod=='GET'){
        // echo "GET";
        doGet($conn);    
    }
    else if($reqMethod == 'POST'){
        // echo "POST";
        doPost($conn,$data);
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
    $query = "select * from bendetails";
    $result = mysqli_query($conn,$query) or die("query failed");
    
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $records[]=$row;
        }   
        echo json_encode($records);
    }
}
function readUser($Received_id,$conn){
    $query = "select * from bendetails where id=".$Received_id;
    $result = mysqli_query($conn,$query) or die("query failed");

    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            echo json_encode($row);
        }
        

    }
    else{
        http_response_code(200);
        echo json_encode(array("message"=>"No Beneficiary Found"));
    }
}
//GET USER Method for Read operations ============GET USER =====================GET USER =============GET USER ====================

 //CREATE USER ===================CREATE USER===========================CREATE USER====================================CREATE USER===========================
 function doPost($conn,$data){
    $benName = isset($data['benname'])?$data['benname']:null;
    $benAccNumber  = isset($data['benacno'])?$data['benacno']:null;
    $accNumber = isset($data['acno'])?$data['acno']:null;
    $bankName = isset($data['bankname'])?$data['bankname']:null;
    $branchName = isset($data['branchname'])?$data['branchname']:null;
    $ifscBen = isset($data['ifsc'])?$data['ifsc']:null;

    if($ifscBen==null||$benName==null || $benAccNumber==null || $accNumber==null || $bankName==null || $branchName==null){
        status_message(400,"Unable To Add New Beneficiary.. Invalid data / Missing Parameters");
        die;
    }    
    $queryDuplicateRecord = "select * from bendetails where benacno = '".$benAccNumber."'";
    $resultDuplicateRecord = mysqli_query($conn,$queryDuplicateRecord)or die("query failed");
    if(mysqli_num_rows($resultDuplicateRecord)>0){
        status_message(400,"Beneficiary with this Account Number Already Exist");
    die;
    }    
    $query = "INSERT INTO bendetails(remacno,acname,benacno,bankname,branchname,ifsc) VALUES ('$accNumber','$benName', '$benAccNumber', '$branchName','$bankName','$ifscBen')";
    $result = mysqli_query($conn,$query) or die("Sql query failed !!");

    status_message(201,"Beneficiary Added Successfully!");

    // {
    //     "acno": 908765432112,
    //     "benname": "Arjun Roy",
    //     "benacno": 123456789012,
    //     "branchname": "manewada",
    //     "bankname": "ICICI",
    //     "ifsc": "123icici"
    // }

}
//CREATE USER ===================CREATE USER===========================CREATE USER====================================CREATE USER===========================

//UPDATE USER ====================UPDATE USER==========================UPDATE USER=================
function doPatch($conn,$data){
    $benAccNumber  = isset($data['benacno'])?$data['benacno']:null;
    $accNumber = isset($data['acno'])?$data['acno']:null;
    $branchName = isset($data['branchname'])?$data['branchname']:null;
    $ifscBen = isset($data['ifsc'])?$data['ifsc']:null;

    if($ifscBen==null|| $benAccNumber==null || $accNumber==null || $branchName==null){
        status_message(400,"Unable To Add New Beneficiary.. Invalid data / Missing Parameters");
        die;
    }        
    
        $query = "update bendetails set branchname='".$branchName."',ifsc='".$ifscBen."' where benacno='".$benAccNumber."'";
        $result = mysqli_query($conn,$query)or die("query failed");
        status_message(200,"Beneficiary Details Updated successfully");
    

        // {
        //     "acno": 908765432112,
        //     "benacno": 1234550509012,
        //     "branchname": "Mahal",
        //     "ifsc": "1223icici"
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
        $query = "select * from bendetails where id=".$id;        
        $result = mysqli_query($conn,$query) or die("query failed");
        if(mysqli_num_rows($result)>0){
            $delQuery = "DELETE FROM bendetails WHERE id =".$id;
            mysqli_query($conn,$delQuery) or die("query failed");
            http_response_code(200);
            echo json_encode(array("Message"=>"Beneficiary Details Deleted","Status"=>"true"));
        }
        else{
            http_response_code(503);
            echo json_encode(array("Message "=>"Id doesn't Exits or Beneficiary Details Already Deleted","status"=>"False"));
        }
        
    }

//DELETE USER Method ========DELETE USER===========================DELETE USER==================DELETE USER===========================
?>