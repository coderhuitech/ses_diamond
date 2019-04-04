<div id="outer-div">
	<h1>Material From Owner to Employee</h1>
	<h1>
		<?php
	 		if($is_authorised)
				echo '<span class="noerror">You are authorised';
			else{
				echo '<span class="error">You are not authorised only owner can use it</span>';
				die();
			}
				
	 	?></h1>
	<div id="container2">
		<div>
			<label for="">Name of Employee</label>
			<?php echo form_dropdown('employee_id',$employees,0,'id="employee_id"');?>
		</div>
		<div>
			<label for="">Material category</label>
			<?Php echo form_dropdown('material_category',$rm_categories,0,'id="material_category"'); ?>
		</div>
		<div id="material-div">
			<label for="">Material</label>
			<?Php echo form_dropdown('materials',$materials,0,'id="materials"'); ?>
		</div>
		<div id="material-value-div">
			<label for="">Material Value</label>
			<input type="text" value="0.000" class="gold-input" id="material-qty"/>
		</div>
	</div>
	<div id="comment-div">
		<textarea rows="4" cols="50" id="comment">
			Transfer from owner 
		</textarea>
	</div>
	<div>
		<input type="submit" value="Submit" id="submit"/>
	</div>
	<div id="report-div"></div>
	<input type="button" value="Refresh Closing Stock" id="refresh-closing-stock"/>
	<div id="closing-stock-div">
	
	</div>
</div>