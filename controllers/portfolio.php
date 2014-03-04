<?php
/*
/-------------------------------------------------------------------------------
/	Description: This page renders the users portfolio  
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/



//------------------------------------------------------------------------------
//standard top-matter, with redirect to index if not signed in yet:
//------------------------------------------------------------------------------
session_start();
require('../includes/helpers.php');
require('../model/model.php');
if(!isset($_SESSION['authorize'])){
	redirect('index');
	exit;
}

//------------------------------------------------------------------------------
// If user is signed in, display the user's portfolio:
//------------------------------------------------------------------------------
else{
	render('header',array('title'=>'C$75'));
	render('portfolio');
	render('footer');
}


?>
