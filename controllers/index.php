<?php
/*
/-------------------------------------------------------------------------------
/	Description: This is the entry point for the user.  Unles they are already 
/		signed in, they will be redirected here every time.     
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/


//------------------------------------------------------------------------------
//	Standard top-matter:
//------------------------------------------------------------------------------
session_start();
require('../includes/helpers.php');
require('../model/model.php');
render('header',array('title'=>'C$75')); 


//------------------------------------------------------------------------------
//	If user is not already signed in, prompt login:
//------------------------------------------------------------------------------
if(!isset($_SESSION['authorize'])){
	render('login');
	
	//if user tries to log in, check name and pw with database
	if(isset($_POST['username']) && $_POST['password']){
	
		//if known user, set username cookie, and redirect them home. 
		if(verifyUser($_POST['username'],$_POST['password'])===true){
			setcookie('username',$_POST['username'], time()+7*24*60*60);
			redirect('home');
			exit;
		}
	}
}
//------------------------------------------------------------------------------
//	If user already has browser open, redirect them to their home page
//------------------------------------------------------------------------------
else{
	redirect('home');
}

render('footer');
?>
