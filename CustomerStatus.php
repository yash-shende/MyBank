<?php session_start();
?>
<?php include 'connection.php'; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Status</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
h1{
  
}
#change-status{
  background-color:#00CC78;
  font-size:20px;
  border:solid 2px black;
  position: fixed;
  bottom: 0; 
  right: 0;
  margin: 40px;
}
#back-btn{
  color:#333;
  padding:10px 20px;
  background-color:#fc4;
  margin-left:20px;
  margin-top : 10%;
}
#p1{
    background-color: black;
    color:white;
    text-align: center;
    width:100%;
    padding:10px;
    margin-top:10%;
}
    .userStatus{
  display:hidden;
  background-color:#fff;
  display:block;  
  width:100%;
 
  padding:20px;
  margin: 30px 10px;
}
h4{
  font-weight: bold;
  font-size:20px;
}
</style>


<body>
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
<center><h1 >Block / Unblock Customers </h1></center>

<?php 
if(isset($_POST["unblock"]))
{
    $code=$_POST["idunblock"];
    $flag=0;
    $sel1="SELECT id FROM dtbase WHERE status1='blocked' ";
    $result=mysqli_query($conn,$sel1);
    if($result)
    {
        while($row=mysqli_fetch_assoc($result))
        {
            if($code==$row["id"])
            {
              $flag=1;
              break;
            }
            else
            {
                 $mess2="No such ID exists in the table. Maybe the user is already unblocked.!!Please enter the correct ID.";
                 $flag=0;
            }
        }
    }
    else{
        echo "Error: " . $sel1 . "<br>" . mysqli_error($conn);
      }
    
if($flag==1)
 {  
    $upd="UPDATE dtbase SET status1='allowed' WHERE id='$code' ";
    if (mysqli_query($conn, $upd))
  {
     echo '<script type="text/javascript">';
     echo 'window.alert("Unblocked !");';
    echo 'window.location.href="adminuserdetails.php";';
    echo '</script>';
  } 
  else 
 {
  echo "Error updating record: " . mysqli_error($conn);
  }
 
  }

} //end of isset
?>
<?php
if(isset($_POST["block"]))
{
    $code=$_POST["idblock"];
    $flag=0;
    
    $sel1="SELECT id FROM dtbase WHERE status1='allowed' ";
    $result=mysqli_query($conn,$sel1);
    if($result)
    {
        while($row=mysqli_fetch_assoc($result))
        {
            if($code==$row["id"])
            {
              $flag=1;
              break;
            }
            else
            {
                 $mess1="No such ID exists in the table!! Maybe the user is already blocked.Please enter the correct ID.";
                 $flag=0;
            }
        }
    }
    else{
        echo "Error: " . $sel1 . "<br>" . mysqli_error($conn);
      }
    
if($flag==1)
 {  
    $upd="UPDATE dtbase SET status1='blocked' WHERE id='$code' ";
    if (mysqli_query($conn, $upd))
  {
    echo '<script type="text/javascript">';
    echo 'window.alert("Blocked !");';
    echo 'window.location.href="adminuserdetails.php";';
    echo '</script>';
  } 
  else 
 {
  echo "Error updating record: " . mysqli_error($conn);
  }
 
 }

}//end of isset

?>




<div class="userStatus" >
<center>
  <table>
  <tbody>
    <tr>
      <td style="background-color:grey;padding:5px 10px;border-right:solid 1px black;">
      <div style="display:block;">
                
                <center><h4>Enter the Code No shown to block any user:</h4></center>
                <div class="col-xs-3">
                <form method="POST" >
                <input type="number" name="idblock" class="form-control" min="0" oninput="validity.valid||(value='');" placeholder="Enter here" required><br>
                <input type="submit" name="block" value="Block" onclick="return confirm('Are you sure, you want to block the user?');" class="btn btn-warning">
                </form>
                <!-- <h5 style="color:red"><?php echo $mess1; ?></h5> -->
                </div>
                </div>
      </td>
      <td style="background-color:grey;padding:5px 10px;border-left:solid 1px black;">
      <div style="">
                <center><h4>Enter the Code No shown to Un-block any user:</h4></center>
                <div class="col-xs-6">
                <form method="POST" >
                <input type="number" name="idunblock" class="form-control" min="0" oninput="validity.valid||(value='');" placeholder="Enter here" required><br>
                <input type="submit" name="unblock" value="Unblock" onclick="return confirm('Are you sure, you want to unblock the user?');" class="btn btn-warning">
                </form>
                <!-- <h5 style="color:red"><?php echo $mess2; ?></h5> -->
                </div>
                </div>
      </td>
    </tr>
  </tbody>
</table>
</center>
<div style="margin-top:20px;">
<center><a href="adminuserdetails.php" id="back-btn"><b>Back</b></a></center>
</div>


<p id="p1"> &copy;onlinebankingsystem2018</p>
</div>

</body>
</html>


