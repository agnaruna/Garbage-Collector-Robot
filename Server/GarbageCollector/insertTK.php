<?php
// iNSERT TASK from app to server
include 'conn.php';

$path=$_POST["path"];
$datetime=$_POST["date_time"];


$stmt=$conn->prepare("insert into tasks_TB (path_name,Date_Time) values (?,?)");

$stmt->bind_param("ss",$path,$datetime);



$stmt->execute();

echo "Success";

$stmt->close();
$conn->close();

?>
