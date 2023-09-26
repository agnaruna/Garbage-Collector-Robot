<?php
include "conn.php";
// arduino robot 

if(isset($_GET['id']) && !empty($_GET['id'])) {
$robotID=$_GET['id'];
$stmt=$conn->prepare("update tasks_TB set RB_status='complete' where  RB_status='pending' and robotID=? ");

$stmt->bind_param("i",$robotID);

$stmt->execute();
$stmt->free_result();

// check for another paths which are pending and 1 h exceed /broken robots paths. 
$stmt=$conn->prepare("SELECT roundid,robotID,path_name FROM tasks_TB WHERE RB_status='pending' AND TIMESTAMPDIFF(HOUR,Date_Time,NOW())>=1 LIMIT 1;");

$stmt->execute();

$stmt->store_result(); //store result

   /* Get the number of rows */
   $num_of_rows = $stmt->num_rows;
if($num_of_rows>0) {
   /* Bind the result to variables */
   $stmt->bind_result($id,$robotid,$path);

   while ($stmt->fetch()) {
        echo $path; // assign another path the previous robot

   }

    $stmt->free_result();
  if($id!=null && $robotid!=null) {

 $stmt=$conn->prepare("UPDATE robotTB SET stat='broken' WHERE robotid=? "); // update that doesn't work  as broken 

$stmt->bind_param("i",$robotid);

$stmt->execute();

 $stmt->free_result();
 
 $stmt=$conn->prepare("UPDATE tasks_TB set robotID=? WHERE roundid=? "); // task update,assign old robot id to new robot id which is going to perform that task

$stmt->bind_param("ii",$robotID,$id);

$stmt->execute();

  }
}else {

  $stmt=$conn->prepare("select roundid,path_name from tasks_TB where RB_status ='not yet'  order by Date_time limit 1 ");

  $stmt->execute();

  $stmt->store_result();

     /* Get the number of rows */
     $num_of_rows = $stmt->num_rows;
  if($num_of_rows>0) {
     /* Bind the result to variables */
     $stmt->bind_result($id,$path);

     while ($stmt->fetch()) {
          echo $path;

     }
    $stmt->free_result();
  $stmt=$conn->prepare("update tasks_TB set RB_status='pending', robotID= ? where  roundid= ? ");

  $stmt->bind_param("ii",$robotID,$id);

  $stmt->execute();
}

}

   /* free results */
   $stmt->free_result();

$stmt->close();
$conn->close();

}


?>
