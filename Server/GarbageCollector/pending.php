<?php

include "conn.php";

$sql=null; $rowcount=null;
$dtsf=null;

if(!isset($_COOKIE["name"]))
  {
header("Location:index.html");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field


    if (isset($_POST['rowid']) && !empty($_POST['rowid'])) {
        $rowid = $_POST['rowid'];
        $stmt=$conn->prepare("DELETE FROM tasks_TB WHERE roundid = ? ");

        $stmt->bind_param("i",$rowid);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Data has been deleted..!');</script>";
    }
  }elseif ($_SERVER["REQUEST_METHOD"]=="GET") {

    if (isset($_GET['page']) && !empty($_GET['page'])) {
       $offset=10*($_GET['page']);

         $sql="select roundid,path_name,Date_Time,RB_status,robotID  from tasks_TB where RB_status='pending' order by Date_Time DESC limit ".$offset." , 10";


    }

  }

  if ($sql==null) {
      $sql="select roundid,path_name,Date_Time,RB_status,robotID  from tasks_TB where RB_status='pending' order by Date_Time DESC limit 10";
  }




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

  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>

 <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css"/>
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"> </script>

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

  <script>
  $(document).ready(function() {

    $('#eventForm').bootstrapValidator({
        container: '#messages',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            date_time: {
                validators: {
                    notEmpty: {
                        message: 'The date is required and cannot be empty'
                    },
                    date: {
                         format: 'YYYY-MM-DD'
                     }
                }
            },
            lsdt: {
                validators: {
                    notEmpty: {
                        message: 'The date is required and cannot be empty'
                    },
                    date: {
                         format: 'YYYY-MM-DD'
                     }
                }
            }



        }
    });
});
          </script>

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
      <a class="navbar-brand" href="homepanel.php">Garbage Collector</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li ><a href="homepanel.php">Home</a></li>
        <li class="dropdown active">
       <a class="dropdown-toggle" data-toggle="dropdown" href="homepanel.php">Status
       <span class="caret"></span></a>
       <ul class="dropdown-menu">
         <li><a href="alltasks.php">All</a></li>
        <li><a href="notyet.php">Not Yet</a></li>
        <li class="active"><a href="pending.php">Pending</a></li>
        <li><a href="complete.php">Complete</a></li>
       </ul>
     </li>
       <li><a href="robotpanel.php">Robot</a></li>
        <li ><a href="AddTask.php">Add Task</a></li>
        <li><a href="deleteall.php">Delete Task</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
       <a class="dropdown-toggle" data-toggle="dropdown" href="homepanel.php"><span class="glyphicon glyphicon-user"></span> Profile
       <span class="caret"></span></a>
       <ul class="dropdown-menu">
         <li><a href="changepassword.php">Change Password</a></li>
        <li><a href="userpanel.php">Add User</a></li>
       </ul>
     </li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center"  style="padding-top: 50px;" >
  <div class="row content">
    <div class="col-sm-4 sidenav">
    <p>  <img src="images-animated-gif.blogspot.com.gif" class="img-responsive" alt="Cinque Terre" style="height=300px; weight=250px;" ></P>
    </div>
    <div class="col-sm-8 text-left" >
      <h2>Tasks</h2>
<hr>

<?php
if($sql <>null) {

$result=$conn->query($sql);

$response=array();


if($result->num_rows > 0) {
?>

			<div class="table-responsive">

		   <table class="table table-striped">
		     <thead>
		       <tr>
		         <th>ID</th>
		         <th>Path Name</th>
		         <th>Date Time</th>
		         <th>Status</th>
            <th>Robot ID</th>
            <th>Operation</th>
		       </tr>
		     </thead>
		     <tbody>


<?php
	 while($row = $result->fetch_assoc()) {


	?>

		       <tr>
		         <td><?php echo  $row["roundid"];?></td>
		         <td><?php echo  $row["path_name"];?></td>
		         <td><?php echo $row["Date_Time"]; ?></td>
		         <td><?php echo $row["RB_status"]; ?></td>
            <td><?php echo $row["robotID"]; ?></td>
            <td><form  id="rowdelete" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><input type="hidden" name="rowid" value="<?php echo$row["roundid"]; ?>">  <button type="submit" class="btn btn-danger"> <span class="glyphicon glyphicon-trash"></span> Delete</button></form></td>

             </tr>
<?php
}

?>
</tbody>
</table>
</div>
<?php
}else {

echo "<h2>Empty</h2>";

}

}

?>



       <center>    <ul class="pagination pagination-sm">
          <?php

          if ($rowcount == null) {
            $sql="select roundid  from tasks_TB ";
            $result=$conn->query($sql);
            $rowcount=$result->num_rows;
          }

          $pagisize=ceil($rowcount/10);
           $pagenum=0;
          if (isset($_GET['page']) && !empty($_GET['page'])) {
            $pagenum=$_GET['page'];
          }

          for ($i=0; $i <$pagisize; $i++) {
   if ($i==$pagenum) {
     ?>
     <li class="active"><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$i; ?>"><?php echo ($i+1) ?></a></li>
   <?php
   } else {

     ?>
   <li><a href="<?php echo $_SERVER['PHP_SELF']."?page=".$i; ?>"><?php echo ($i+1) ?></a></li>



     <?php   }  }
     $conn->close();

          ?>
        </ul>
   </center>

			 <hr>
		 </div>
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
