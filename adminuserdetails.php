<?php session_start();
error_reporting(0);
?>
<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>  
    <style>

        #sidenav{
    list-style-type: none;
    padding:0px;
    margin:0px;
    background-color:#2ecc71;
    width:120px;
    height:90%;
    position:fixed;
    margin-top:-20px;
}
#sidenav li a{
    text-decoration: none;
    color:white;
    display:block;
    padding:20px 20px;
}
li a:hover{
    background-color: #1abc9c;
    color:white;
    
}
table {
    border-collapse: collapse;
    width:100%;
    height:70%;
    font-size:16px;
    padding:15px;

}
table,td{    
    border: 1px solid #ddd;
    padding:15px;
}
td {
    height:50%;
    width:10%;
    text-align:center;
    padding:15px;

}
tr:hover {background-color: #bdc3c7;}
tr:nth-child(even) {background-color: #f2f2f2;}
tr:nth-child(even):hover {background-color: #bdc3c7;}




#change-status{
  background-color:#00CC78;
  font-size:20px;
  border:solid 2px black;
  position: fixed;
  bottom: 0; 
  right: 0;
  margin: 40px;
}

</style>
<body>
<title>User Registered</title>
<link rel="shortcut icon" href="logo.ico" />
   <meta charset="utf-8">
   <meta name="viewport" content="width=1024">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<nav class="navbar navbar-expand-md navbar-inverse">
   
   <div class="navbar-header">
     <a class="navbar-brand " href="homepage.php" ><img src="home.png" height="20px" width="20px"></a>
     <a class="navbar-brand " href="homepage.php">MyBank</a>
   </div>

   <ul class="nav navbar-nav">
     <li class="nav-item">
     <a class="nav-link" href="features.php">Features</a>
     </li>
     <li class="nav-item">
       <a class="nav-link" href="about.php">About</a>
     </li>
    <li class="nav-item">
       <a class="nav-link" href="contact.php">Contact Us</a>
    </li>
   </ul>
   <ul class="nav navbar-nav navbar-right">
       <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
   </ul>
   <ul class="nav navbar-nav navbar-right">
     <li><a href="adminDashboard.php"><?php echo  $_SESSION["email"]; ?></a></li>
   </ul>
  
</nav>
<ul id="sidenav">
<li><a href="adminDashboard.php">Home</a></li>
<li><a href="adminuserdetails.php">Registered Users</a></li>
<li><a href="adminAddMoney.php">Approve Money to User Account</a></li>
<li><a href="adminWithMoney.php">Approve Withdrawal of Money</a></li>
<li><a href="adminTransfer.php">Approve Transfer of Funds</a></li>
<li><a href="adminATMapprove.php">ATM Card Requests</a></li>
</ul>


<div style="margin-left:12%;margin-top:5%;padding:1px 16px;height:80px;">
<p> Here the admin can see the details of all the users who have registered for online banking
and also has the right to block any user who violates any rule of online banking</p>
<h3> Details of Registered Users:</h3>
<table>
<tr>
<td>CodeNo.</td>
<td>First Name</td>
<td>Last Name</td>
<td>Email</td>
<td>A/C No.</td>
<td>D.O.B</td>
<td>Gender</td>
<td>Country</td>
<td>Status</td>
</tr>
<?php 

$sql="SELECT * FROM dtbase";
$result=mysqli_query($conn,$sql);
if(mysqli_query($conn,$sql))
{
    if(mysqli_num_rows($result) > 0)
    {
        while($row=mysqli_fetch_assoc($result))
        {
          echo "<tr><td>".$row["id"]."</td><td>".$row["firstname"]."</td><td>".$row["lastname"]."</td><td>".$row["email"]
          ."</td><td>".$row["acNo"]."</td><td>".$row["dob"]."</td><td>".$row["gender"]."</td><td>".$row["country"]."</td><td>".$row["status1"];
        }
        echo "</table>";
    }
    else
    {
        echo "<h2 style=\"color:red\">No user registered yet!!</h2>";
    }   
}
?>





</div>

<a id="change-status" href="CustomerStatus.php">Change User Status</a>

</body>