<?php 
/*
/------------------------------------------------------------------------------
/	Includes functions that are called in other sections of the program
/	created by: Scott Owen
/--------------------------------------------------------------------------------
*/





//*******************************************************************************
//this function calls the desired file in the views folder.  Called from the 
//	controllers, and optionally takes in an array called data.  
//*******************************************************************************
function render($template, $data = array()){

	$path = '../views/'.$template . '.php';
	if (file_exists($path)){
		extract($data);
		require($path);
	}	
}

//*******************************************************************************
//this function redirects the user to the specified page.  !!modify with __DIR__
//  to simplify
//*******************************************************************************
function redirect($controller){
	// redirect user to home page, using absolute path, per
	//http://us2.php.net/manual/en/function.header.php
	$host = $_SERVER["HTTP_HOST"];
	$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
	header("Location: http://$host$path/{$controller}.php");
}

//***************************************************************************
//this function will input an array of stock symbols, query the yahoo server 
//and lookup the stock name, symbol, current price, and last trade. It will 
//return an associative array of these values. 
//*************************************************************************** 
function yahooStocks($ticker){
	//create a string of ticker values to format for yahoo api.
	foreach($ticker as $index =>$tick)
	{
		if($index==0)
			$tickString = $tick;
		else
			$tickString = $tickString.",".$tick;		
	}
	//lookup quotes, creating a new array for each line of quotes.  
	$url		 = "http://download.finance.yahoo.com/d/quotes.csv?s={$tickString}&f=s0nl1d1&e=.csv&h=0";
	$urlh        = fopen($url,"r");	
	$stocks = array();  
	//ensure fgetcsv goes through the entire file line by line
	while(!feof($urlh)){
		$stockTemp   = fgetcsv($urlh);
		$stock = array( 'symbol'=>$stockTemp[0],
						'name'  =>$stockTemp[1],
						'price' =>$stockTemp[2],
						'date'  =>$stockTemp[3]);
		
		array_push($stocks,$stock);
	}
	fclose($urlh);
	//return an array of arrays indexing the quote information.  
	return $stocks;
}	
?>
