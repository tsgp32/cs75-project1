<?php
/*
/------------------------------------------------------------------------------
/	Description: This file displays the login form to the user with the option
/		to register as a new user. 
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
?>
<form action = "<?=$_SERVER["PHP_SELF"] ?>" method = "post" role="form">
 
 <div class="form-inline">
  <div class="form-group">
    <label for="exampleInputEmail1"></label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name = "username">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1"></label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name = "password">
  </div>
 </div>
 </br>
 <div class="form-inline">
  <div class="form-group">
    <label for="exampleInputEmail1"></label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="First Name" name = "firstname">
  </div>  

  <div class="form-group">
    <label for="exampleInputEmail1"></label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Last Name" name = "lastname">
  </div>   
 </div>
 </br>
 <button type="submit" class="btn btn-default">Register!</button>
</form>

