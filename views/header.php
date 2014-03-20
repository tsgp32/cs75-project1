<?php
/*
/-------------------------------------------------------------------------------
/	Description: This page renders the standard header for each page   
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
   		<meta name="viewport" content="width=device-width, initial-scale=1">
    	<!-- Bootstrap -->
    	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    	<link rel="stylesheet" type="text/css" href="../views/styles.css">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>

	<body class>
		<div class ="container">
			<h1 class = "banner">Welcome to <?=$title?></h1>
			<ul class = "nav nav-pills">				
		<? if(isset($_SESSION['authorize'])): ?>			
				<li><a href="../controllers/home.php">   	Home			</a></li>
				<li><a href="../controllers/buysell.php">	Trade Stocks	</a></li>
				<li><a href="../controllers/portfolio.php"> My portfolio	</a></li>
				<li><a href="../controllers/logout.php"> 	Logout 			</a></li>
		<? else: ?>
				<li><a href="../controllers/home.php"> 		Login 			</a></li>
				<li><a href="../controllers/register.php"> 	Register 		</a></li>
		<? endif ?>
			</ul>
			</br>
			</br>
			
