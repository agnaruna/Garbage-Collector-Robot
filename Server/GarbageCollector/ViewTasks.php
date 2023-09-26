<?php

include "conn.php";

$sql=null;

$sql="select roundid,path_name,Date_Time,RB_status  from tasks_TB order by Date_Time DESC limit 60";

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tasks</title>
  <link rel="shortcut icon" href="Robot 3-48.png" type="image/x-icon" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 650px}

    /* Set gray background color and 100% height */
    .sidenav {

      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }

    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }

    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;}
    }
  </style>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.html">Garbage Collector</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li ><a href="index.html">Home</a></li>
        <li class="active"><a href="ViewTasks.php">Tasks</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="about.html">About</a></li>
      </ul>

    </div>
  </div>
</nav>

<div class="container-fluid text-center"  style="padding-top: 50px;" >
  <div class="row content">
    <div class="col-sm-4 sidenav">
    <p>  <img src="images-animated-gif.blogspot.com.gif" class="img-responsive" alt="Cinque Terre" ></P>
    </div>
    <div class="col-sm-8 text-left" >

      <h2>Tasks</h2>

			<div class="table-responsive">
		   <table class="table table-striped">
		     <thead>
		       <tr>
		         <th>ID</th>
		         <th>Path Name</th>
		         <th>Date Time</th>
		         <th>Status</th>

		       </tr>
		     </thead>
		     <tbody>

  <?php
	if($sql <>null) {

	$result=$conn->query($sql);

	$response=array();


	if($result->num_rows > 0) {

	 while($row = $result->fetch_assoc()) {


	?>

		       <tr>
		         <td><?php echo  $row["roundid"];?></td>
		         <td><?php echo  $row["path_name"];?></td>
		         <td><?php echo $row["Date_Time"]; ?></td>
		         <td><?php echo $row["RB_status"]; ?></td>

		       </tr>
<?php
}

}else {

echo "ERROR:01";

}

}
$conn->close();

?>

		     </tbody>
		   </table>
		   </div>
			 <hr>
		 </div>
    </div>

  </div>
</div>

<footer class="container-fluid text-center">
  <p>Create By Samitha Dulanjana Silva</p>
</footer>

</body>
</html>
<!--

<ul class="nav nav-pills">
<li class="active" ><a href="json_getfrom.html" >Get Json Data</a></li>
<li><a href="formgetPath.html" >Get Path</a></li>
<li><a href="insertform.html" >Insert Path</a></li>
<li><a href="delete_form.html" >delete Path</a></li>
<li><a href="formupdatestat.html" >Update status</a></li>
</ul>

-->
