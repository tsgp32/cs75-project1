<?php 
/*
/------------------------------------------------------------------------------
/	Includes functions that are called in other sections of the program
/	created by: Scott Owen
/--------------------------------------------------------------------------------
*/
require_once('../../swiftmailer/lib/swift_required.php');




//*******************************************************************************
//This function calls the desired file in the views folder.  Called from the 
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
//This function redirects the user to the specified page.  !!modify with __DIR__
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
//This function will input an array of stock symbols, query the yahoo server 
//	and lookup the stock name, symbol, current price, and last trade. It will 
//	return an associative array of these values. 
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


function emailUser($user,$code){
	
	extract($user);
	
  	//	Create activation Link
	$host = '192.168.39.128'; //$_SERVER["HTTP_HOST"];
	$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
	$link = "http://$host$path/emailValidate.php?email=$username&code=$code";
		
	// Create the Transport
	$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,"ssl");
	$transport->setUsername('srowen84@gmail.com');
	$transport->setPassword('CEssnaZ172');

	// Create the Mailer using the created Transport
	$mailer = Swift_Mailer::newInstance($transport);
	
	// Create the message
  	$body = "Hi ".$firstname.",
  	
  			Please click on the link below to activate your account.
  			
  			".$link."
  				
  				
  				
  			Thanks,
  			the finance team"; 				
  				
	$message = Swift_Message::newInstance();
	$message->setSubject('Activate you C$75 Account');
    $message->setFrom(array('srowen84@gmail.com' => 'Scott'));
    $message->setTo(array($username => $firstname));
    $message->setBody($body);

	// Either send message, or report an error
	if(!$mailer->send($message)){
		echo("The email confirmation wasn't send...work harder scott");
		exit;		
	} 	
}


function validEmail($email){
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
    	// Return Error - Invalid Email
    	$msg = 'The email you have entered is invalid, please try again.';
     	echo('<div class="statusmsg">'.$msg.'</div>');
     	exit;
	}
}
?>
