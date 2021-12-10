<?php
    header('Content-Type: application/json');
    header('Acess-Control-Allow-Origin: *');
    header('Acess-Control-Allow-Method: GET');
    header('Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method,Authorization,X-Requested-With');
    
    $data = json_decode(file_get_contents("php://input"),true);
    
    
    
    $account_number  = $data['acc_number'];
    //if (!preg_match('/^[0-9]*$/', $account_number))die('invalid Account Number');
    
    // connection setup 
    include '../connection.php'; 

if($_SERVER["REQUEST_METHOD"]=="GET"){ 
    
    if($account_number==null){
        $account_number =$_GET['anum'];
        $length = strlen($_GET['anum']);
    
        if($_GET['anum']){
            $acc_num = $_GET['anum'];
            $where = "WHERE remacno =".$acc_num;
        }
        else{
            $acc_num="";
            $where = "WHERE remacno =".$account_number;
        }
    }
    else{
        $length = strlen($account_number);
        $where = "WHERE remacno =".$account_number;
    }
    

if($length==12){

    $query = "SELECT updated FROM realtransaction ".$where;
    //echo $query;
    
    $result = mysqli_query($conn,$query) or die("Bad Request!");


    if(mysqli_num_rows($result)>0){
        //$output = mysqli_fetch_all($result,MYSQLI_ASSOC);        
        
        while($row=mysqli_fetch_assoc($result))
        {
           $bal=$row["updated"];
        }
        http_response_code(200);       
        echo json_encode(array("account_number"=>$account_number,"balance "=>$bal));
    }
    else{
        http_response_code(400);
        echo json_encode(array('Message'=>'No Account Exist ','Status '=>'False'));
    }
}   //end of if where account number is not entered
    else{
        http_response_code(204);
        echo json_encode(array('Message'=>'invalid Account Number','Status '=>'False'));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("Status"=>"Bad Request"));
}

// json Req BODY
// {
//     "acc_number": 67674674747
// }

?>