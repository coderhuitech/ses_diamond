$.msgBox({
   	title: "Save JOB",
   	content: "Confirm to save?",
   	type: "confirm",
   	buttons: [{ value: "Yes" }, { value: "No" }],
   	success: function (result) {
       	if (result == "Yes") {
			//your code here for yes
			return;
       	}//end of yes
		if (result == "No") {
       		return;
       	}//end of no
  	}//end of success
});