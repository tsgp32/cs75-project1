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

<div>	
	<form action = "<?=$_SERVER["PHP_SELF"]; ?>" method = "post" />
		<table>
			<tr>
				<td> Username: </td>
				<td> <input type  = "text"     name = "username" value = "<?=$temp_user?>"/> </td>
				<td> Password: </td>
				<td> <input type  = "password" name = "password" /> </td>
			</tr>
			<tr>
				<td>Stay logged in?<input type = "checkbox" name = "remember_me" value = "true" /></td>
				<td colspan ='2' align = 'center'> <input type  = "submit"   value= "  Login! " /> </td>
			</tr>
		</table>
	</form>
</div>  

<!-- if user is not a member, redirect to register page -->
<div>
	<form action = "register.php" method = "get" />
		<input type = 'submit' value = "Register Now!" />
	</form>
</div>
