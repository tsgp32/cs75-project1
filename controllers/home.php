<?
/*
/-------------------------------------------------------------------------------
/	Description: Controller for the users homepage.   
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/



//------------------------------------------------------------------------------
//	Standard top-matter, with redirect to index if not signed in yet:
//------------------------------------------------------------------------------
session_start();
require('../includes/helpers.php');
require('../model/model.php');
if(!isset($_SESSION['authorize'])){
	redirect('index');
	exit;
}

//------------------------------------------------------------------------------
//	If user is signed in, display the user's homepage:
//------------------------------------------------------------------------------
else{
	render('header',array('title'=>'C$75')); 
	render('home');
	render('stock_lookup');
	render('footer');
}
?>
