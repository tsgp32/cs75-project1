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

<form action = "<?=$_SERVER["PHP_SELF"] ?>" method = "post" />
	<table>
		<tr>
			<td> Username: </td>
			<td> <input type  = "text"     name = "username" /> </td>
			<td> Password: </td>
			<td> <input type  = "password" name = "password" /> </td>
		</tr>
				    
		<tr>
			<td> First Name: </td>
			<td> <input type  = "text"     name = "firstname" />  </td>
			<td> Last Name: </td>
			<td> <input type  = "text"     name = "lastname"  /> </td>
		</tr>
				    
		<tr>
			<td colspan ='2' align = 'center'> <input type  = "submit"   value= "Register!" /> </td>
		</tr>
</form>

