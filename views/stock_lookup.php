<?php
/*
/------------------------------------------------------------------------------
/	Description: This deals with a stock look up form.  The user can enter multiple
/		stocks and the result will be sent to the yahooStocks function.     
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
?>

<!-- Add form to allow user to look up stock symbol -->
<div align = 'center'>
<form action = "<?=$_SERVER["PHP_SELF"]?>" method = 'post' >
	<input type = 'text' name = 'symbols' /></br>
	<input type = 'submit' name = 'submit' value = "Look up">
</form>
</div>


<!-- Display stock lookup info to user -->
<table align = 'center' cellpadding = '5px'>
	<thead class = 'stocks'>
		<tr>
			<th>Stock Name</th>
			<th>Stock Symbol</th>     
			<th>Current Price(USD)</th>    
			</tr>
	</thead>
	<tbody>
	
	
<!-- Convert string input to array-->
<? if(isset($_POST['symbols'])){

	$symbols_parsed = preg_split("/[\s,]+/", $_POST["symbols"]);
	$stocks = yahooStocks($symbols_parsed);
	//print_r($stocks);
	foreach($stocks as $stock){?>
		<tr class = "stocks">
			<td><?=$stock['name']?>  </td>
			<td><?=$stock['symbol']?></td>
			<td><?=$stock['price']?> </td>
		</tr>
	<?}
	unset($_POST['symbols']);
	unset($symbols_parsed);
	unset($stocks);
}?>
	</tbody>
</table>

</br>
</br>

