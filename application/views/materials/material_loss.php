<div id="outer-div">
	<h1>Material Loss Adjustment</h1>
	<div id="inner-div-1">
		<div id="left-div">
			<div>
				<label for="">Name of Employee</label>
				<?php echo form_dropdown('employee_id',$employees,0,'id="employee-id"');?>
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
				<input type="text" value="0.000" class="gold-input" id="material-value"/>
			</div>
			<div id="comment-div">
				<textarea rows="4" cols="50" id="comment">
					Transfer between employees 
				</textarea>
			</div>
			<div>
				<input type="submit" value="Submit" id="submit"/>
			</div>
		</div>
		<div id="right-div">
			This is my right div
		</div>
	</div>
	<div id="report-div"></div>
	<input type="button" class="hidden" value="Refresh Closing Stock" id="refresh-closing-stock"/>
	<div id="closing-stock-div">
	
	</div>
	
</div>