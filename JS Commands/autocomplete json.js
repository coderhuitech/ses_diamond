// the jquery code for 
$( "#bill-no" ).autocomplete({
			//auto complete with extra parameters
			source: function(request, response) {
	            $.ajax({
	                url: base_url+"/cash_inward_controller/get_bills_by_customer",
	                dataType: "json",
	                data: {
	                    term : request.term,
	                    cust_id : $("#customer-id").val()
	                },
	                success: function(data) {
	                    response(data);
	                }
	            });
        	},
			 minLength: 2,
			focus: function(event, ui) {
            	$("#bill-no").val(ui.item.label); //product field will show the product list only
            	return false;
        	},
    		select: function (event, ui) {
				$('#bill-no').text(ui.item.label);
				$("#billed-lc").val(ui.item.billed_lc); // box number will show the value
				$("#cleared-lc").val(ui.item.cleared_lc); // box number will show the value
				return false;
			}
		});
		/***************************************************************************/
		//action against controller
		function get_bills_by_customer(){
		$result=$this->bill_model->select_bills_by_customer($_GET['term'],$_GET['cust_id']);
		$row_array=array();
		$return_arr=array();
		if($result==NULL){
			$row_array['label']="No Bill exists";
			$row_array['billed_lc']="";
			$row_array['cleared_lc']="";
		}
		
		
		//$models=array();
		foreach($result->result() as $row){
			$row_array['label']=$row->bill_no;
			$row_array['billed_lc']=$row->bill_labour_charge;
			$row_array['cleared_lc']=$row->Cash_cleared;
			array_push($return_arr,$row_array);
		}
		echo json_encode($return_arr);
	}
		