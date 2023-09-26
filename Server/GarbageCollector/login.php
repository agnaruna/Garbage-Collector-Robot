<?php
include "conn.php";
$user= $_POST['user'];
$password= $_POST['pass'];

$stmt = $conn->prepare("select* from rclogin where user= ? and password= ?");
$stmt->bind_param("ss",$user,$password);
$stmt->execute();

$stmt->store_result();

   /* Get the number of rows */
   $num_of_rows = $stmt->num_rows;

if ($num_of_rows>0) {
   setcookie("name",$user,time()+60*60*2);
   $stmt->free_result();
$stmt->close();
$conn->close();
header("Location:homepanel.php"); // redirect to homepage
	   exit;
}else{
	header("Location:index.html");
	}


?>
