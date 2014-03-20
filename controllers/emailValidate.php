<?php
/*
/-------------------------------------------------------------------------------
/	Description: This function is called when the user responds to the activation
/		code, and updates the users profile confirmCode variable from NULL to 
/		the specified value.  
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/

//-------------------------------------------------------------------------------
//	Standard top-matter:
//-------------------------------------------------------------------------------
session_start();
require('../includes/helpers.php');
require('../model/model.php');
render('header',array('title'=>'C$75')); 

//check to see if email and code fields are valid
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['code']) && !empty($_GET['code'])){
    activateUser($_GET['email'],$_GET['code']);
	$_SESSION['authorize'] = 1;
    redirect('index');
}

render('footer');







?>
