<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} 
else {  
        include '../connection.php';

        if($_SERVER["REQUEST_METHOD"]=="POST"){
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = base64_encode($_SERVER['PHP_AUTH_PW']);
        if (!preg_match('/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/',$username))die('invalid email Format');
        
        if($username=="" || $password=="") die("Invalid Credentials !!");
        
        $query = "SELECT id,firstname,lastname FROM dtbase WHERE (email = '$username' AND psword='$password')";
        
        $result = mysqli_query($conn,$query) or die("query failed");

        

        if(mysqli_num_rows($result)>0){
            $output = mysqli_fetch_all($result,MYSQLI_ASSOC);
            $user_arr=array(
                
                "Message" => "Sucessfully logged in !!",
                "Status" => true,
             );
             http_response_code(200);
            echo json_encode($user_arr);
        }
        else{
            http_response_code(400);
            echo json_encode(array('Message'=>'Invalid Email-id or Password ','Status '=>'False'));
        }
        
        }        
        else{
            http_response_code(400);
            echo json_encode(array("Status"=>"Bad Request"));
        }    
}
?>