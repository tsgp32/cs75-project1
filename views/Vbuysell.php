<?php
/*
/------------------------------------------------------------------------------
/	Description: This page renders a form to purchase or sell stock.   
/ 
/	Created by: Scott Owen
/--------------------------------------------------------------------------------
*/
?>

<form class="form-inline" role="form" action = "<?=$_SERVER["PHP_SELF"]?>" method = 'post' >
  
  <div class="form-group">
    <label class="sr-only" for="exampleInputEmail2">Stock Symbol</label>
    <input type="text" class="form-control" id="exampleInputEmail2" placeholder= "Stock Symbol" name = 'symbol' >
  </div>
  
  <div class="form-group">
    <label class="sr-only" for="exampleInputPassword2">Shares</label>
    <input type="text" class="form-control" id="exampleInputPassword2" placeholder="Shares" name = 'shares'>
  </div>
 
  <select class="form-control" name = "buysell">
    <option>Buy</option>
    <option>Sell</option>
  </select>
  <input type = 'hidden' name = 'execute' value = 'true'>
  <button type="submit" class="btn btn-default">Make Trade</button>
</form>
