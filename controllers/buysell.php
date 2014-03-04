<?php
/*
/-------------------------------------------------------------------------------
/	Description: Controller for the page that allows the user to purchase stocks.     
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
// If user is signed in, allow them to trade stocks
//------------------------------------------------------------------------------
else{
	render('header',array('title'=>'C$75')); 
	//display form fields
	render('Vbuysell');
	
	//Once the buysell form has been submitted communicate with the database
	if(isset($_POST['execute'])){
		//remove all characters from list of inputs
		$symbols_parsed = preg_split("/[\s,]+/", $_POST["symbol"]);
		
		//look up current stock value
		$stock = yahooStocks($symbols_parsed);

		if($_POST['buysell'] == 'Buy'){
			buystock($stock,$_POST['shares']);
			redirect('portfolio');
			exit;
		}		
		elseif($_POST['buysell']=='Sell'){
			sellstock($stock,$_POST['shares']);	
			redirect('portfolio');
			exit;
		}		
		else{
			echo('Complete the form!');
		}	
	}
	render('footer');

}?>



