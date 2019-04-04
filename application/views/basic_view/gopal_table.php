<table border="0" width="80%" cellpadding="0" cellspacing="0" id="product-table">
<tr>
<!--<th width="10" class="table-header-repeat line-left"><div align="center"><a href="">&nbsp;</a> </div></th>-->
<th width="30" class="table-header-repeat line-left"><div align="center"><a href="">SL No</a> </div></th>
<th width="40" class="table-header-repeat line-left"><div align="center"><a href="index.php?tth_ref=tth_ref">Code</a> </div></th>
<!--<th width="74" class="table-header-repeat line-left minwidth-1"><div align="center"><a href="index.php?tth_earn_money=tth_earn_money">Customer Name</a></div></th>-->
<th width="74" class="table-header-repeat line-left minwidth-1"><div align="center"><a href="index.php?tth_earn_money=tth_earn_money">Product Name</a></div></th>
<th width="120" class="table-header-repeat line-left"><div align="center"><a href="index.php?tth_value=tth_value">Deccription</a></div></th>
<th width="50" class="table-header-repeat line-left"><div align="center"><a href="">Unit</a></div></th>
<th width="50" class="table-header-repeat line-left"><div align="center"><a href="">Price</a></div></th>
<th width="100" class="table-header-repeat line-left"><div align="center"><a href="">Edit</a></div></th>
<th width="40" class="table-header-repeat line-left"><div align="center"><a href="">Delete</a></div></th>
</tr>

<?php


$sql=mysql_query("SELECT pm.pm_id,
       pm.pm_code,
       pm.pm_name,
       pm.pm_description,
       um.um_name,
       pm.pm_price
  FROM product_master pm, uom_master um
 WHERE pm.uom_id = um.um_id and pm.cm_id='".$mc_id."' and (pm.pm_code REGEXP '^$param'
 or pm.pm_name REGEXP '^$param')order by pm_id desc");

/*$qry1=mysql_query($sql);
$row=mysql_num_rows($qry1);*/
$counter="";
$counter=1;			
while($row=mysql_fetch_array($sql))
{
	$id=$row['pm_id'];
	$pm_code=$row['pm_code'];
	$pm_name=$row['pm_name'];
	$pm_description=$row['pm_description'];
	
	$um_name=$row['um_name'];
	$pm_price=$row['pm_price'];
	
	
	//date('d-m-Y, g:i a',strtotime($article_date));
	
	
	?>
	<tr id="<?php echo $id; ?>" class="edit_tr">
	<!--<td>&nbsp;</td>-->
	<td><?php echo $counter; ?></td>
	
	<td class="edit_td">
	<span id="pm_code_<?php echo $id; ?>" class="text"><?php echo $pm_code; ?></span>
	<input type="text" value="<?php echo $pm_code; ?>" class="editbox" id="pm_code_input_<?php echo $id; ?>"/>
	</td>
	
	<td class="edit_td">
	<span id="pm_name_<?php echo $id; ?>" class="text"><?php echo $pm_name; ?></span> 
	<input type="text" value="<?php echo $pm_name; ?>" class="editbox" id="pm_name_input_<?php echo $id; ?>"/>
	</td>
	
	
	<td class="edit_td">
	<span id="pm_description_<?php echo $id; ?>" class="text"><?php echo $pm_description; ?></span> 
	<input type="text" value="<?php echo $pm_description; ?>" class="editbox" id="pm_description_input_<?php echo $id; ?>"/>
	</td>
	
	<td class="edit_td">
	<span id="um_name_<?php echo $id; ?>" class="text"><?php echo $um_name; ?></span> 
	<!--<input type="text" value="<?php echo $cust_phone; ?>" class="editbox" id="cust_phone_input_<?php echo $id; ?>"/>-->
	</td>
	
	<td class="edit_td">
	<span id="pm_price_<?php echo $id; ?>" class="text"><?php echo $pm_price; ?></span> 
	<input type="text" value="<?php echo $pm_price; ?>" class="editbox" id="pm_price_input_<?php echo $id; ?>"/>
	</td>

	<td class="Edit" id="<?php echo $id; ?>"><a href="#" title="Edit" class="icon-5 info-tooltip"></a> </td>
	<td class="del" id="<?php echo $id; ?>">
	<a href="" title="Delete" class="icon-2 info-tooltip"></a>
	</td>
	</tr>
	<?php
	$counter++;
}
?>
</table>