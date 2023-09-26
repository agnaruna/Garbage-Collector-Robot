<?php 
//  get details from server to app using json

include "conn.php";

$key=$_POST["key"];


$valArray=explode(',',$key);

$sql=null;

if($key=="all") {

$sql="select roundid,path_name,Date_Time,RB_status  from tasks_TB order by Date_Time DESC limit 60";


}else if($valArray[0]=="status") {

$sql="select roundid,path_name,Date_Time,RB_status from tasks_TB where RB_status ='".$valArray[1]."' order by Date_Time DESC limit 60 ";


}elseif($valArray[0]=="datetime") {
	
	$last=new DateTime($valArray[1]);
	
$start = new DateTime($valArray[1]);
 $start->modify('first day of this month');
    

$stmt=$conn->prepare("select roundid,path_name,Date_Time,RB_status from tasks_TB order by Date_Time DESC limit 60 ");


$stmt->execute();


$stmt->store_result();

   /* Get the number of rows */
   $num_of_rows = $stmt->num_rows;

if($num_of_rows>0) {

   /* Bind the result to variables */
   $stmt->bind_result($id, $path_name, $datetime, $status);
   
   $response=array();

   while ($stmt->fetch()) {
       
       $dtv =new DateTime($datetime) ;
       
       if($dtv>=$start and $dtv <=$last) {
			
			 array_push($response,array("roundid"=>$id,"path_name"=>$path_name,"Date_Time"=>$datetime,"RB_status"=>$status));			
			     
       }
       
   }
   echo json_encode(array("server_response"=>$response)); // json encoding
   

}

   /* free results */
   $stmt->free_result();

}


if($sql <>null) {

$result=$conn->query($sql);

$response=array();


if($result->num_rows > 0) {

 while($row = $result->fetch_assoc()) {
       
        array_push($response,array("roundid"=>$row["roundid"],"path_name"=>$row["path_name"],"Date_Time"=>$row["Date_Time"],"RB_status"=>$row["RB_status"]));
    
    }
    
   echo json_encode(array("server_response"=>$response));
    
}else {
	
echo "ERROR:01";

}

}
$conn->close();

?>