<?php
/*
/------------------------------------------------------------------------------
/	Description: Includes all the functions to access the model 
/		that will be returned to the controller in future
/		work will use join and transaction commands
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
define("CASH","10000");
define("DB_HOST","localhost");
define("DB_NAME","finance75");
define("DB_USER", "jharvard");
define("DB_PW", "crimson");





//***************************************************************************
//This function will take in the user info as an array, 
//	and add the user to the database.  it will return the number of rows 
//	affected for error handling.
//***************************************************************************
function addUser($user_info){
	$cash = 10000;
	extract($user_info);
	//connect to database with PDO and se predefined un/pw for testing purposes:
 	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	
	//query the database for the username entered in the form field using a prepared statement: 
	try{
		$STH = $DBH->prepare("INSERT INTO finance75.users(username,password,firstname,lastname,cash)
			    VALUES(:username,:password,:firstname,:lastname,:cash)");
		//bind the value entered by user into the query string
		$STH ->bindvalue(':username' , $username);
		$STH ->bindvalue(':password' , $password);
		$STH ->bindvalue(':firstname', $firstname);			
		$STH ->bindvalue(':lastname' , $lastname);
		$STH ->bindvalue(':cash'     , $cash);
			
		//execute the query
		$STH ->execute();
	}
	
	catch(Exception $e){
		echo('unable to add user');
		exit;
	}
	
	// count rows affected:
	$_SESSION['authorize']=1;
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['cash'] =$cash;
	$DBH = null;
	
}
//***************************************************************************
// This function queries the database to check if user is already a member.
//***************************************************************************
function verifyUser($username,$password){
	//connect to database with PDO and se predefined un/pw for testing purposes:
 	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	
	//query the database for the username entered in the form field using a prepared statement: 
	$STH = $DBH->prepare("SELECT * FROM finance75.users WHERE username = :username");
	//bind the value entered by user into the query string
	$STH ->bindvalue(':username',$username);
	//execute the query
	$STH ->execute();
	
	//use the fetch command to set the returned values into an associate array
	$STH->setFetchMode(PDO::FETCH_ASSOC);  
	$row =$STH->fetch();
	$DBH = null;		
	//search to see if the corresponding password is correct
	if($row['password']==$password){
		$_SESSION['authorize']=1;
		$_SESSION['username'] = $username;
		$_SESSION['cash'] = $row['cash'];
		
		return true;
	}
}

//***************************************************************************
//This function will take in user name and return an array of all use users 
//	financial information
//***************************************************************************
function getPortfolio($username){

   	//connect to database with PDO and se predefined un/pw for testing purposes:
 	$DBH_lookup = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	//query the database for the username entered in the form field using a prepared statement: 
	$STH = $DBH_lookup->prepare("SELECT symbol, price, shares FROM finance75.portfolio WHERE username = :username");
	//bind the value entered by user into the query string
	$STH ->bindvalue(':username',$_SESSION['username']);
	//execute the query
	$STH ->execute();
	//use the fetch command to set the returned values into an associate array
	$stocks =$STH->fetchAll();

	return $stocks;
	
	}
//***************************************************************************
//This function inputs a stock to purchase as an array with the fields
//	symbol, price, shares.  It adds it to the database and updates the current 
//	cash value.  
//***************************************************************************


function buystock($stock, $shares){
	
	//need to remove the indexing that is added in preg_split
	$mystock=$stock[0];

	if($mystock['price']*$shares>$_SESSION['cash']){
		echo('You do not have enough cash for this purchase');
		exit;	
	}
	
	$date = date('Y-m-d');
	//connect to database with PDO:
	try{
		$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
		$DBH->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e){
		echo('Could not connect to database');
		exit;
	}
	//------------------------------------------------------------------------
	//		QUERY THE DATABASE TO SEE IF YOU ALREADY OWN THE STOCK
	//------------------------------------------------------------------------	

	try{
		//query the database for the username entered in the form field using a prepared statement: 
		$STHq = $DBH->prepare("SELECT * FROM finance75.portfolio WHERE username= :username AND symbol=:symbol");
		//bind the value entered by user into the query string
		$STHq ->bindvalue(':username', $_SESSION['username']);
		$STHq ->bindvalue(':symbol', $mystock['symbol']);
		//execute the query
		$STHq ->execute();
	}
	catch(Exception $e){
		echo('could not query database');
	}
	if($STHq->rowCount()>0){
		$row = $STHq->fetch(PDO::FETCH_ASSOC);
	}


	//------------------------------------------------------------------------
	//		UPDATE ROW IF YOU DO OWN THE STOCK
	//------------------------------------------------------------------------
	if(isset($row)){
	
		try{
			$STH1 = $DBH->prepare("UPDATE finance75.portfolio SET price=:price, shares=:shares 
				WHERE username=:username AND symbol=:symbol");
			
			//bind the value entered by user into the query string
			$STH1 ->bindvalue(':username', $_SESSION['username']); echo($_SESSION['username'].'</br>');
			$STH1 ->bindvalue(':symbol', $row['symbol']); echo($row['symbol'].'</br>');
			$STH1 ->bindvalue(':shares', $row['shares'] + $shares); echo($row['shares'] + $shares.'</br>');
			$STH1 ->bindvalue(':price' , $row['price']  + $mystock['price']*$shares);echo($row['price']  + $mystock['price']*$shares.'</br>');

			$STH1->execute();
		}
		catch(Exception $e){
			echo('Could not update');
		}
	}

	//------------------------------------------------------------------------
	//		ADD ROW IF YOU DO NOT OWN THE STOCK
	//------------------------------------------------------------------------
	else{
		$STH = $DBH->prepare("INSERT INTO finance75.portfolio(username,symbol,price,shares)
			VALUES(:username,:symbol,:price,:shares)");	
			
		//bind the value entered by user into the query string
		$STH ->bindvalue(':username' , $_SESSION['username']);
		$STH ->bindvalue(':symbol'   , $mystock['symbol']);
		$STH ->bindvalue(':price'    , $mystock['price']*$shares);
		$STH ->bindvalue(':shares'   , $shares);
		$STH ->execute();
	}

	
	//------------------------------------------------------------------------
	//		UPDATE THE CASH 
	//------------------------------------------------------------------------
	$_SESSION['cash'] = $_SESSION['cash'] - ($shares*$mystock['price']);
	$STH2 = $DBH->prepare("UPDATE finance75.users SET cash=:cash WHERE username=:username");

	$STH2 ->bindvalue(':cash', $_SESSION['cash']);	
	$STH2 ->bindvalue(':username',$_SESSION['username']);			
	$STH2 ->execute();	
	

}


//***************************************************************************
//This function inputs a stock to purchase as an array with the fields
//	symbol, price, shares.  It checks to see if the user is able to sell the 
//	stock, if so completes the transaction and updates the cash balance.  
//***************************************************************************	
function sellstock($stock, $shares){
	
	//need to remove the indexing that is added in preg_split
	$mystock=$stock[0];	
	
	//connect to database with PDO:
	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	$DBH ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//------------------------------------------------------------------------
	//		QUERY THE DATABASE TO SEE IF YOU ALREADY OWN THE STOCK
	//------------------------------------------------------------------------	
	
	try{
		//query the database for the username entered in the form field using a prepared statement: 
		$STHq = $DBH->prepare("SELECT * FROM finance75.portfolio WHERE username= :username AND symbol=:symbol");
		//bind the value entered by user into the query string
		$STHq ->bindvalue(':username', $_SESSION['username']);
		$STHq ->bindvalue(':symbol', $mystock['symbol']);
		//execute the query
		$STHq ->execute();
	}
	catch(PDOException $e){
		echo('Unable to connect to database: ' . $e->getMessage());
		exit;
	}
	if($STHq->rowCount()>0){
		$row = $STHq->fetch(PDO::FETCH_ASSOC);
	}
	
	//------------------------------------------------------------------------
	//		EITHER UPDATE STOCK OR RETURN ERROR TO USER TO SELL LESS
	//------------------------------------------------------------------------
	if(isset($row)){
		if($row['shares']>$shares){
			$STH = $DBH->prepare("UPDATE finance75.portfolio SET shares=:shares, price=:price WHERE username=:username AND symbol=:symbol");

			//bind the value entered by user into the query string
			$STH ->bindvalue(':username' , $_SESSION['username']);
			$STH ->bindvalue(':symbol'   , $mystock['symbol']);
			$STH ->bindvalue(':shares'   , $row['shares']-$shares);
			$STH ->bindvalue(':price'	 , $row['price']-$mystock['price']*$shares);
			//execute the query
			$STH ->execute();
		}
		elseif($row['shares']==$shares){
			$STH = $DBH->prepare("DELETE FROM finance75.portfolio WHERE username=:username AND symbol=:symbol");
			
			//bind the value entered by user into the query string
			$STH ->bindvalue(':username' , $_SESSION['username']);
			$STH ->bindvalue(':symbol'   , $mystock['symbol']);		
			//execute the query
			$STH ->execute();	
		}
		else{
			echo('unable, you do not own enough shares for this purchase');
			exit;		
		}
		
		//update the total cash reserve for the user
		$_SESSION['cash'] = $_SESSION['cash'] + ($shares*$mystock['price']);
		$STH2 = $DBH->prepare("UPDATE finance75.users SET cash=:cash WHERE username=:username");

		$STH2 ->bindvalue(':cash', $_SESSION['cash']);	
		$STH2 ->bindvalue(':username',$_SESSION['username']);			
		$STH2 ->execute();
	}
	else{
		echo('you do not own any shares in this stock!');

		exit;
	}
	

}
//***************************************************************************
//This function resets the users information to contain no stocks and the 
//	original cash balance.   
//***************************************************************************
function reset_user($username){
	$cash = 10000;
	//connect to database with PDO:
	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	
	$STH = $DBH->prepare("DELETE * FROM finance75.porfolio WHERE username=:username");
	//bind the value entered by user into the query string
	$STH ->bindvalue(':username' , $_SESSION['username']);
	//execute the query
	$STH ->execute();	
	
	//update the total cash reserve for the user
	$STH2 = $DBH->prepare("UPDATE finance75.users SET cash=:cash WHERE username=:username");

	$STH2 ->bindvalue(':cash', $cash);	
	$STH2 ->bindvalue(':username',$_SESSION['username']);			
	$STH2 ->execute();	


}
//***************************************************************************
//This function adds the transaction to the users history.  The function inputs
//	are the $stock array and the $type which is either a buy or sell.  The goal
//	of this function is to be able to display an entire list of previous purchases
//	that may be  
//***************************************************************************
function addTransaction($stock, $shares, $type){
	//need to remove the indexing that is added in preg_split
	$mystock=$stock[0];	
	$date = date('Y-m-d');	
	//connect to database with PDO:
	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	$DBH ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	
	try{
		//query the database for the username entered in the form field using a prepared statement: 
			$STH = $DBH->prepare("
				INSERT INTO finance75.transactions(username,symbol,date,price,shares,transType)
				VALUES(:username,:symbol,:date,:price,:shares,:transType)");
				
		//bind the value entered by user into the query string
		$STH ->bindvalue(':username',	$_SESSION['username']);
		$STH ->bindvalue(':symbol', 	$mystock['symbol']);
		$STH ->bindvalue(':date',		$date);
		$STH ->bindvalue(':price',		$mystock['price']);
		$STH ->bindvalue(':shares',		$shares);
		$STH ->bindvalue(':transType',	$type);
		//execute the query
		$STH ->execute();
	}
	catch(PDOException $e){
		echo('Unable to upload transaction:'. $e->getMessage());
		exit;
	}
}
?>
	
