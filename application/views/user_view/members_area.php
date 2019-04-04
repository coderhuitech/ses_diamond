<style type="text/css">
	#outer_div{
		padding-left: 20px;
		padding-top: 20px;
	}
	table td{
		  border: 1px solid #2e74b2;
		  padding: 5px;;
	}
	table th{
		border: 1px solid black;
		padding: 5px;;
		background-color: #0b0b0b;
		color: white;
	}
</style>
<div id="outer_div">
	<?php $user_picture='img/users/'.$this->session->userdata('user_id').'.jpg';?>
	<?php echo img(array('src'=>$user_picture,'id'=>'search','class'=>'no_print remove','height'=>'200px','border'=>'1','alt'=>'Remove'));?>
	<?php
	$emp_id=$this->session->userdata('employee_id');
	if($emp_id!=28) {
		echo $report_table;
	}

	?>
	<!--<img alt="Rs." src="<?php echo base_url();?>/img/thank-you-note.png" >-->
</div>


