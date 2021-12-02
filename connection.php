<?php
$servername="localhost";
$username="admin";
$pd="admin";
$dbname="bank";

$conn=mysqli_connect($servername,$username,$pd,$dbname);

if(!$conn)
{
    die ("Connection failed".mysqli_connect_error());
}
?>
