$sql = "UPDATE finance75.user_stocks SET purchase_price = :price,purchase_date = :date, 
            shares = :shares,  
            WHERE username = :username, stock_symbol=:symbol";
$stmt = $pdo->prepare($sql);                                  
$stmt->bindParam(':filmName', $_POST['filmName'], PDO::PARAM_STR);       
$stmt->bindParam(':filmDescription', $_POST['$filmDescription'], PDO::PARAM_STR);    
$stmt->bindParam(':filmImage', $_POST['filmImage'], PDO::PARAM_STR);
// use PARAM_STR although a number  
$stmt->bindParam(':filmPrice', $_POST['filmPrice'], PDO::PARAM_STR); 
$stmt->bindParam(':filmReview', $_POST['filmReview'], PDO::PARAM_STR);   
$stmt->bindParam(':filmID', $_POST['filmID'], PDO::PARAM_INT);   
$stmt->execute(); 

		$sql = "UPDATE `finance75.user_stocks`
				SET `purchase_price` = :price,
					`purchase_date`  = :date,
					`shares`         = :shares
				WHERE `username      = :username,
					  `stock_symbol  = :symbol";

