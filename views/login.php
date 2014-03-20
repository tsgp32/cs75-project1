<?php
/*
/------------------------------------------------------------------------------
/	Description: This file displays the login form to the user with the option
/		to register as a new user. 
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
	if(isset($_COOKIE["username"])){
		$temp_user = $_COOKIE["username"];
	}
	else{
		$temp_user ="";
	}
?>


<form class="form-horizontal" role="form" action = "<?=$_SERVER["PHP_SELF"]; ?>" method = "post">
  <!--Email entry -->
  <div class="form-group">
    <label for="inputEmail3" class="control-label"></label>
    <div class="col-sm-4">
      <input type="email" class="form-control" id="inputEmail3" placeholder="Email"  name = "username" value = "<?=$temp_user?>">
    </div>
  </div>
  <!--Password entry -->
  <div class="form-group">
    <label for="inputPassword3" class="control-label"></label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="inputPassword3" placeholder="Password" name = "password">
    </div>
  </div>
  
  <!--Checkbox and submit -->
  <div class="form-inline">
  
      <div class="checkbox">
        <label>
          <input type="checkbox" name = "remember_me" value = "true"> Remember me
        </label>
      </div>
      <button type="submit" class="btn btn-default">Sign in</button>

  </div>
</form>

