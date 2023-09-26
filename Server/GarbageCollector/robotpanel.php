<?php

include "conn.php";

$sql=null; $rowcount=null;


if(!isset($_COOKIE["name"]))
  {
header("Location:index.html"); // redirect if cookies nt available
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field

    if (isset($_POST["robotID"]) && !empty($_POST["robotID"])) {

      $rid=$_POST["robotID"];

      $stmt=$conn->prepare("insert into robotTB(robotid) values (?)");

      $stmt->bind_param("i",$rid);

      $stmt->execute();
      echo "<script type='text/javascript'>alert('Robot has been added..!');</script>";
    }elseif (isset($_POST['rowidb']) && !empty($_POST['rowidb'])) {
        $rowid = $_POST['rowidb'];
        $stmt=$conn->prepare("update robotTB set stat='broken' where robotid=? ");

        $stmt->bind_param("i",$rowid);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Status has been changed..!');</script>";
    }elseif(isset($_POST['rowida']) && !empty($_POST['rowida'])) {
        $rowid = $_POST['rowida'];
        $stmt=$conn->prepare("update robotTB set stat='available' where robotid=? ");

        $stmt->bind_param("i",$rowid);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Status has been changed..!');</script>";
      }elseif (isset($_POST['rowdel']) && !empty($_POST['rowdel'])) {
        $rowid = $_POST['rowdel']; // delete robot /remove
        $stmt=$conn->prepare("delete from robotTB where robotid=?");

        $stmt->bind_param("i",$rowid);
        $stmt->execute();

      }
  }elseif ($_SERVER["REQUEST_METHOD"]=="GET") {

    if (isset($_GET['page']) && !empty($_GET['page'])) {
       $offset=5*($_GET['page']);
      $sql="select *  from robotTB limit ".$offset." , 5";

    }

  }

  if ($sql==null) {
      $sql="select * from robotTB limit 5"; // show only 5 records per page
  }




 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Robot Panel</title>
  <link rel="shortcut icon" href="Robot 3-48.png" type="image/x-icon" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


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
          robotID: {
              validators: {
                  notEmpty: {
                      message: 'The Robot ID is required and cannot be empty'
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
        <li class="dropdown">
       <a class="dropdown-toggle" data-toggle="dropdown" href="homepanel.php">Status
       <span class="caret"></span></a>
       <ul class="dropdown-menu">
         <li><a href="alltasks.php">All</a></li>
        <li><a href="notyet.php">Not Yet</a></li>
        <li><a href="pending.php">Pending</a></li>
        <li><a href="complete.php">Complete</a></li>
       </ul>
     </li>
       <li class="active"><a href="robotpanel.php">Robot</a></li>
        <li><a href="AddTask.php">Add Task</a></li>
        <li><a href="deleteall.php">Delete Task</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
       <a class="dropdown-toggle" data-toggle="dropdown" href="homepanel.php"><span class="glyphicon glyphicon-user"></span> Profile
       <span class="caret"></span></a>
       <ul class="dropdown-menu">
          <li><a href="#">Change Password</a></li>
         <li ><a href="userpanel.php">Add User</a></li>
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
      <h2>Add Robot</h2>
<hr>
      <form id="eventForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" style="padding-top: 50px;">
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Robot ID:</label>
          <div class="col-sm-6">
            <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="number" name="robotID" min="1" max="999" data-bind="value:replyNumber" class="form-control" id="email" placeholder="Enter Robot ID" required="true">
          </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default"> <span class="glyphicon glyphicon-ok"></span> Add Robot</button>
          </div>
        </div>
        <div class="col-sm-6 col-sm-offset-2">
            <div id="messages"></div>
        </div>
      </form>
      <div class"col-sm-8">
        <hr>
<h2>Robots</h2>
<hr>
</div>


			<div class="table-responsive">

		   <table class="table table-striped">
		     <thead>
		       <tr>
		         <th>Robot ID</th>
             <th>Status</th>
              <th></th>
              <th></th>
		       </tr>
		     </thead>
		     <tbody>

  <?php
	if($sql <>null) {

	$result=$conn->query($sql);

	$response=array();


	if($result->num_rows > 0) {

	 while($row = $result->fetch_assoc()) {
//get the values as a row to display the robots,sql statement implemented before $sql

	?>

		       <tr>
		         <td><?php echo  $row["robotid"];?></td>
             <td><?php echo  $row["stat"];?></td>
<?php
$status=$row["stat"];
 if ($status=="available") {

?>

            <td><form  id="rowdelete" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><input type="hidden" name="rowidb" value="<?php echo$row["robotid"]; ?>">  <button type="submit" class="btn btn-warning"> <span class="glyphicon  glyphicon-ban-circle"></span> Broken</span></span></button></form></td>
<?php
}else {

?>
<td><form  id="rowdelete" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><input type="hidden" name="rowida" value="<?php echo$row["robotid"]; ?>">  <button type="submit" class="btn btn-success"> <span class="glyphicon glyphicon-ok-circle"></span> Available</button></form></td>

<?php
}
?>
<td><form  id="rowdelete" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><input type="hidden" name="rowdel" value="<?php echo$row["robotid"]; ?>">  <button type="submit" class="btn btn-danger"> <span class="glyphicon glyphicon-trash"></span> Remove</button></form></td>


             </tr>
<?php
}

}else {

echo "ERROR:01";

}

}

?>

		     </tbody>
		   </table>
		   </div>

       <center>    <ul class="pagination pagination-sm">
          <?php

          if ($rowcount == null) {
            $sql="select *  from robotTB ";
            $result=$conn->query($sql);
            $rowcount=$result->num_rows;
          }

          $pagisize=ceil($rowcount/5);
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
