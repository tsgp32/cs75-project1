<?php
/*
/-------------------------------------------------------------------------------
/	Description: Controller page for user logout
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/


//------------------------------------------------------------------------------
//	Standard top-matter, with redirect to the Index if not logged in:
//------------------------------------------------------------------------------
require('../includes/helpers.php');
session_start();
if(!isset($_SESSION['authorize'])){
	redirect('index');
	exit;
}

//------------------------------------------------------------------------------
//	If user is signed in, log the user out:
//------------------------------------------------------------------------------
else{
	session_destroy();
	echo('<h2>You are logged out!</h2>');
	render('footer');
}
?>



