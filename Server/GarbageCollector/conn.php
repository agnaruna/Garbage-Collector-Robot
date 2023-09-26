<?php
// create connectio between db and server
$servername="localhost";
$username="id9021035_root";
$pass="root123";
$Db_name="id9021035_gacorobot";

$conn=new mysqli($servername,$username,$pass,$Db_name);

if($conn->connect_error){
die("Connection Faild".$conn->connect_error);
}


?>
