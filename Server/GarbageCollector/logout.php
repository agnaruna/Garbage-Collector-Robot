<?php
if(isset($_COOKIE["name"]))
  {

   setcookie("name",'',time()-60*60*2);
  }

header("Location:index.html");

?>
