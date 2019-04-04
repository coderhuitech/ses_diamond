<div id="product-add-edit">
	<form action="" id="myform">
	<div>
	<label>Product</label><input type="text" class="properText" id="product-code" placeholder="Model No" title="Model" pattern="[A-Z][0-9]{4}"/>
	</div>
	
	<div>
	<label>Description</label><input type="text" id="product-description" placeholder="Description"/>
	</div>
	<div>
		<label>Product Category</label><?php echo form_dropdown('product_category',$product_category,0,'id="product-category" '); ?>
	</div>
	<div>
		<label>Price Code</label><input type="text" pattern="([A-Z])" id="price-code" required="yes" placeholder="" title="Price Code" class="properText"/>
	</div>
	<input type="button" value="submit" id="submit"/>
	</form>
</div>