<?php

include "conn.php";

$sql=null; $rowcount=null;


if(!isset($_COOKIE["name"]))
  {
header("Location:index.html");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field

    if (isset($_POST["user"]) && !empty($_POST["user"])) {

      $user=$_POST["user"];
        $oldPassword=$_POST["oldpass"];
          $newPassword=$_POST["newpass"];

      $stmt=$conn->prepare("update rclogin set password=? where user=? and password=?");

      $stmt->bind_param("sss",$newPassword,$user,$oldPassword);

      $stmt->execute();
      echo "<script type='text/javascript'>alert('Password has been changed..!');</script>";
    }elseif (isset($_POST['rowid']) && !empty($_POST['rowid'])) {
        $rowid = $_POST['rowid'];
        $stmt=$conn->prepare("DELETE FROM rclogin WHERE user = ? ");

        $stmt->bind_param("s",$rowid);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('User has been removed..!');</script>";
    }
  }elseif ($_SERVER["REQUEST_METHOD"]=="GET") {

    if (isset($_GET['page']) && !empty($_GET['page'])) {
       $offset=5*($_GET['page']);
      $sql="select user  from rclogin limit ".$offset." , 5";

    }

  }

  if ($sql==null) {
      $sql="select user from rclogin limit 5";
  }




 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Change Password</title>
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
          user: {
              validators: {
                  notEmpty: {
                      message: 'The email address is required and cannot be empty'
                  },
                  emailAddress: {
                      message: 'The email address is not valid'
                  }
              }
          },
          oldpass: {
              validators: {
                  notEmpty: {
                      message: 'The Password is required and cannot be empty'
                  },
                  stringLength: {
                      min: 5,
                      message: 'The Password must be more than 5 characters long'
                  }
              }
          },
          newpass: {
              validators: {
                  notEmpty: {
                      message: 'The Password is required and cannot be empty'
                  },
                  stringLength: {
                      min: 5,
                      message: 'The Password must be more than 5 characters long'
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
       <li><a href="robotpanel.php">Robot</a></li>
        <li><a href="AddTask.php">Add Task</a></li>
        <li><a href="deleteall.php">Delete Task</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
         <li class="dropdown active">
       <a class="dropdown-toggle" data-toggle="dropdown" href="homepanel.php"><span class="glyphicon glyphicon-user"></span> Profile
       <span class="caret"></span></a>
       <ul class="dropdown-menu">
          <li class="active"><a href="changepassword.php">Change Password</a></li>
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
      <h2>Change Password</h2>
<hr>
      <form id="eventForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" style="padding-top: 50px;">
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Email:</label>
          <div class="col-sm-6">
            <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="email" name="user" class="form-control" id="email" placeholder="Enter email" required="true">
          </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="pwd">Old Password:</label>
          <div class="col-sm-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input type="password" name="oldpass" class="form-control" id="pwd" placeholder="Enter password" required="true">
          </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="pwd">New Password:</label>
          <div class="col-sm-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input type="password" name="newpass" class="form-control" id="pwd" placeholder="Enter password" required="true">
          </div>
          </div>
        </div>


        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default"> <span class="glyphicon glyphicon-edit"></span> Change</button>
          </div>
        </div>
        <div class="col-sm-6 col-sm-offset-2">
            <div id="messages"></div>
        </div>
      </form>
      <div class"col-sm-8">
        <hr>
<h2>Users</h2>
<hr>
</div>



			<div class="table-responsive">

		   <table class="table table-striped">
		     <thead>
		       <tr>
		         <th>User Name</th>
             <th>Operation</th>
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
		         <td><?php echo  $row["user"];?></td>
            <td><form  id="rowdelete" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><input type="hidden" name="rowid" value="<?php echo$row["user"]; ?>">  <button type="submit" class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> Remove</button></form></td>

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
            $sql="select user  from rclogin ";
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
