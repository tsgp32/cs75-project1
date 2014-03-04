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
<html>
    <head>
      <link rel="stylesheet" type="text/css" href="../views/styles.css">
	</head>

	<body class = "main">
		<h1 class = "banner">Welcome to <?=$title?></h1>
		
		<? if(isset($_SESSION['authorize'])): ?>			
			<ul class = "nav">
				<li><a href="../controllers/home.php">   	Home			</a></li>
				<li><a href="../controllers/buysell.php">	Trade Stocks	</a></li>
				<li><a href="../controllers/portfolio.php"> My portfolio	</a></li>
				<li><a href="../controllers/logout.php"> 	Logout 			</a></li>
			</ul>
		<? endif ?>
			
