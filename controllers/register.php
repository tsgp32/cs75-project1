<?php
/*
/-------------------------------------------------------------------------------
/	Description: This file provides a form to register the user, adds them
/		to the database, and will then redirect them to the index page.   
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
render('register');

//--------------------------------------------------------------------------------
//	Create a user arry to be added to table
//--------------------------------------------------------------------------------
if(isset($_POST['username'])){
	$user = array('username'  => $_POST['username'],
				  'password'  => $_POST['password'],
				  'firstname' => $_POST['firstname'],
				  'lastname'  => $_POST['lastname']);	
	addUser($user);
	redirect('index');
	}

render('footer');
?>
