
<div id="outer-div">
	<div id="final-result">
	
	
	<form action="" id="new-order-form">
	<div>
		<?php
		echo '<label>Agent </label>';
		echo form_dropdown('agent_id',$all_agents,'AG000','id="new-agent-id"');
		?>
	</div>
	<div id="new-customer-div"></div>
	<div id="order_div">
		<div id="working-div">
			<div class="hidden">
				<label for="user_id">User Name</label>
				<?php echo form_input('user_id',$user_id,'id="user_id"');?>
			</div>
			<!--<div id="agent-div">
				<label for="">Agent Name</label>
				<?php echo form_dropdown('agent_id',$agents,0,'id="agent_id"') ;?>
			</div>-->
			<!--<div id="customer-div">
				<label for="">Customer Name</label>
				<input type="text" id="customer-id" name="customer_id" placeholder="Customer Id" title="Enter customer ID"/>
				<span id="customer-name">Customer Name</span>
				
			</div>-->
			<div id="user-key-div">
				<label for="">Order Key</label>
				<?php echo form_input('user_key',set_value('user_key',$user_key),'id="user_key" readonly="yes"') ;?>
			</div>
			
			
			<div id="order-date-div">
				<label for="">Order Date</label>
				<?php echo form_input('order_date',set_value('order_date',$current_date),'id="order_date" title="Enter Order Date" placeholder="DD/MM/YYYY" required="yes" readonly="true"') ;?>
				</br>
				<label for="">Delivery Date</label>
				<?php echo form_input('delivery_date',set_value('delivery_date',$delivery_date),'id="delivery_date" title="Enter Delivery Date" placeholder="DD/MM/YYYY" required="yes"') ;?>
			</div>
			<div id="order_input_div">
				<?php echo form_input('model_no',set_value('model_no'),'id="model-no" title="Model" placeholder="Model No" required="yes"') ;?>
				<?php echo form_input('model_size',set_value('model_size'),'id="model-size" title="Model Size" placeholder="Size" required="yes"') ;?>
				<?php echo form_input('appx_gold',set_value('appx_gold'),'id="appx-gold" title="Total Gold" class="numericOnly gold-input" placeholder="Approx Gold" required="yes"') ;?>
				<!--<select id="rm-id" name="rm_id">
					 <option value="42" selected>90 Gold</option>
					 <option value="48">92 Gold</option>
				</select>-->
				<?php echo form_dropdown('rm_id',$gold,$default_gold_id); ?>
				<?php echo form_input('qty',set_value('qty'),'id="qty" placeholder="QTY" title="Qty" class="integerOnly" required="yes"') ;?>
				<?php echo form_input('description',set_value('description'),'id="description" placeholder="Description" title="Enter Description Here" class=""') ;?>
				
			</div>
			<div id="result-div">
				
			</div>
		</div>
	</div>
	</form>
	<div id="result_wrapper">
		<div id="order_table_div"></div>
		<div id="result_wrapper_right_div"> 
			<input type="button" id="save-order" value="Save Order"/>
		</div>
	</div>
	</div>
</div>