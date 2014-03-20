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


<form class="form-inline" role="form" align = "center" method = "post">
  <div class="form-group" >
    <label class="sr-only" for="exampleInputEmail2">Stock Symbol</label>
    <input type="text" class="form-control" id="exampleInputEmail2" placeholder="Stock Symbol" name = "symbols">
  </div>
  <button type="submit" class="btn btn-default">Look Up</button>
</form>


	
	
<!-- Display stock lookup info to user -->
<? if(isset($_POST['symbols'])):?>
	
	<div class = "container">
	<table class="table table-striped" >
	<thead>
		<tr>
			<th class="col-sm-1">Stock Name</th>
			<th class="col-sm-1">Stock Symbol</th>     
			<th class="col-sm-1">Current Price(USD)</th>    
		</tr>
	</thead>
	<tbody>
	
	<?
	// Convert string input to array	
	$symbols_parsed = preg_split("/[\s,]+/", $_POST["symbols"]);
	$stocks = yahooStocks($symbols_parsed);
	?>
	
	<?foreach($stocks as $stock):?>
		<tr>
			<td><?=$stock['name']?>  </td>
			<td><?=$stock['symbol']?></td>
			<td><?=$stock['price']?> </td>
		</tr>
	<? endforeach ?>
	
	<?
		unset($_POST['symbols']);
		unset($symbols_parsed);
		unset($stocks);
	?>
	</tbody>
</table>
</div>	
<?endif?>



