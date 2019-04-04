<script type="text/javascript">
	
	$(function() {
		$( "#bill-no" ).autocomplete({
			//auto complete with extra parameters
			source: function(request, response) {
	            $.ajax({
	                url: base_url+"/bill_controller/get_all_bills_ajax",
	                dataType: "json",
	                data: {
	                    term : request.term,
	                    //agent_id : $("#agent_id").val()
	                },
	                success: function(data) {
	                    response(data);
	                }
	            });
        	},
			focus: function(event, ui) {
            	$("#bill-no").val(ui.item.label); //product field will show the product list only
            	return false;
        	},
    		select: function (event, ui) {
        		$("#bill-no").val(ui.item.label); //product will show the productID
				$("#bill-no").val(ui.item.value); // box number will show the value
				return false;
			}
		});
		
	});//end of function

</script>

<div id="outer-div">
	<div id="">
		<form action="display_bill" method="post">
		<label for="">Bill No </label>
		<input name="bill_no" type="text" placeholder="Bill No" id="bill-no">
		<input type="submit" id="submit" value="submit">
		</form>
	</div>
</div>