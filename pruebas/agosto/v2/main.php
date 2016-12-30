<div class='col-lg-12 col-xs-12' >
<?php

  if(isset($_SESSION["usr"])){
    
    if(!isset($_REQUEST["logout"])){
      include("principal.php");
    }
    else{
      include("logout.php");
    }

  }
  else{
    include("login.php");
  }

?>
</div>