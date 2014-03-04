<?php
/*
/------------------------------------------------------------------------------
/	Description: This page will display the users portfolio in a table. 
/		it will be called by render and the stocks will be passed in 
/		through the function calls.
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
?>

<?
	//create an empty array to be filled with stock values.  
	$stockquery = array();
	
	//get the users current portfolio
	$portfolio = getPortfolio($_SESSION['username']);
	
	//only add items if the user has a nontrivial portfolio.
	if(count($portfolio)>0){
		$createTable = 'true';
		//create an array of only the stock symbols in the portfolio.  
		foreach($portfolio as $stock){
			array_push($stockquery,$stock['symbol']);
		}
		//query yahoo for current value of investments
		$portfolioValue = yahooStocks($stockquery);	
	}

?>

<!--create table that displays users stocks -->
<table align = 'center' cellpadding = '5px'>
	<thead class = 'stocks' class = 'my_stocks'>
		<tr>
			<th>Name				</th>
			<th>Symbol				</th>     
			<th>Shares				</th>
			<th>Current Value (USD) </th>  
			<th>Gains/lost			</th>
		</tr>
	</thead>
	
	<tbody align = 'center'>
		<tr>
			<td>**CASH**</td>
			<td></td>
			<td></td>
			<td><?=$_SESSION['cash']?></td>

<?	if(isset($createTable)): ?>
		<? foreach($portfolio as $index=>$stock):?>			
		<? $temp= $portfolioValue[$index];?>

		<tr>
			<td><?=$temp['name'];?>        												</td>
			<td><?=$stock['symbol']; ?> 												</td>
			<td><?=$stock['shares'];?> 													</td>
			<td><?=$temp['price']*$stock['shares'];?>  									</td>
			<td><?=number_format($temp['price']*$stock['shares']-$stock['price'],2);?>	</td>
		</tr>
		<? endforeach ?>
         
<? endif ?>

	</tbody>
</table>  
