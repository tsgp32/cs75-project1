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







//***************************************************************************
//This function will take in the user info as an array, 
//	and add the user to the database.  it will return the number of rows 
//	affected for error handling.
//***************************************************************************
function addUser($user_info){
	//initially give the user $1000 to invest
	$cash = 1000;
	extract($user_info);
	//connect to database with PDO and se predefined un/pw for testing purposes:
 	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	
	//query the database for the username entered in the form field using a prepared statement: 
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
	// count rows affected:
	$rows_affected = $STH->rowCount();
	$_SESSION['authorize']=1;
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['cash'] =$cash;


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
	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");

	//------------------------------------------------------------------------
	//		QUERY THE DATABASE TO SEE IF YOU ALREADY OWN THE STOCK
	//------------------------------------------------------------------------	

	//query the database for the username entered in the form field using a prepared statement: 
	$STHq = $DBH->prepare("SELECT shares FROM finance75.user_stocks WHERE username= :username AND stock_symbol=:symbol");
	//bind the value entered by user into the query string
	$STHq ->bindvalue(':username', $_SESSION['username']);
	$STHq ->bindvalue(':symbol', $mystock['symbol']);
	//execute the query
	$STHq ->execute();
	$row = $STHq->fetch(PDO::FETCH_ASSOC);

	//------------------------------------------------------------------------
	//		UPDATE ROW IF YOU DO OWN THE STOCK
	//------------------------------------------------------------------------
	if($STHq->rowCount() > 0){
		$STH1 = $DBH->prepare("UPDATE finance75.user_stocks SET shares=:shares WHERE username=:username AND stock_symbol=:symbol");
		//bind the value entered by user into the query string
		$STH1 ->bindvalue(':username', $_SESSION['username']); 
		$STH1 ->bindvalue(':symbol', $mystock['symbol']); 
		$STH1 ->bindvalue(':shares', $row['shares']+$shares); 

		$STH1 ->execute();
	}

	//------------------------------------------------------------------------
	//		ADD ROW IF YOU DO NOT OWN THE STOCK
	//------------------------------------------------------------------------
	else{
		$STH = $DBH->prepare("INSERT INTO finance75.user_stocks(username,stock_symbol,purchase_price,purchase_date,shares)
			VALUES(:username,:symbol,:price,:date,:shares)");	
			
		//bind the value entered by user into the query string
		$STH ->bindvalue(':username' , $_SESSION['username']);
		$STH ->bindvalue(':symbol'   , $mystock['symbol']);
		$STH ->bindvalue(':price'    , $mystock['price']);
		$STH ->bindvalue(':date'     , $date);
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

	//------------------------------------------------------------------------
	//		QUERY THE DATABASE TO SEE IF YOU ALREADY OWN THE STOCK
	//------------------------------------------------------------------------	
	
	//query the database for the username entered in the form field using a prepared statement: 
	$STHq = $DBH->prepare("SELECT * FROM finance75.user_stocks WHERE username = :username AND stock_symbol = :symbol");
	//bind the value entered by user into the query string
	$STHq ->bindvalue(':username',$_SESSION['username']);
	$STHq ->bindvalue(':symbol', $mystock['symbol']);
	//execute the query
	$STHq ->execute();
	$row = $STHq->fetch(PDO::FETCH_ASSOC);
	
	//------------------------------------------------------------------------
	//		EITHER UPDATE STOCK OR RETURN ERROR TO USER TO SELL LESS
	//------------------------------------------------------------------------
	if($row){
		if($row['shares']>$shares){
			$STH = $DBH->prepare("UPDATE finance75.user_stocks SET shares=:shares WHERE username=:username AND stock_symbol=:symbol");

			//bind the value entered by user into the query string
			$STH ->bindvalue(':username' , $_SESSION['username']);
			$STH ->bindvalue(':symbol'   , $mystock['symbol']);
			$STH ->bindvalue(':shares'   , $row['shares']-$shares);
			//execute the query
			$STH ->execute();
		}
		elseif($row['shares']=$shares){
			$STH = $DBH->prepare("DELETE FROM finance75.user_stocks WHERE username=:username AND stock_symbol=:symbol");
			
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
	$cash = 1000;
	//connect to database with PDO:
	$DBH = new PDO("mysql:host=localhost;dbname = finance75","jharvard","crimson");
	
	$STH = $DBH->prepare("DELETE * FROM finance75.user_stocks WHERE username=:username");
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
?>
	
