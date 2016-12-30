<div class='col-lg-12 col-xs-12' >
<?php

  if(SessionSGO::exists("usr_id")){
    
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