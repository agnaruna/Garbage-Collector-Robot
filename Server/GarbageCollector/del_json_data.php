<?php

// COMMUNICATE BETWEEN APP AND SERVER TO DELETE
include "conn.php";

$roundid=$_POST["rid"];
$idArray=explode(':',$roundid);

$stmt=$conn->prepare("DELETE FROM tasks_TB WHERE roundid = ? ");

$stmt->bind_param("i",$id); 

foreach($idArray as $idArray){
    $id=$idArray;
    $stmt->execute();
}

echo "Data has been deleted..!";

$stmt->close();
$conn->close();

?>
