<?php
/*
/------------------------------------------------------------------------------
/	Description: This page renders a form to purchase or sell stock.   
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
?>

<form action = "<?=$_SERVER["PHP_SELF"]?>" method = 'post' >
	Stock Ticker: <input type = 'text' name = 'symbol' /> 
	Shares: <input type = 'text' name = 'shares'>
	<select name = 'buysell'>
		<option value = ''></option>
		<option value = 'Buy'>Buy</option>
		<option value = 'Sell'>Sell</option>
	</select>
	<input type = 'hidden' name = 'execute' value = 'true'>
	<input type = 'submit' name = 'submit' value = "Trade Now!">
</form>


