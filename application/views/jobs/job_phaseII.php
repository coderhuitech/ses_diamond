

<div id="outer-div">
	<div id="left-div">
		<input type="button" id="show-material-in-hand-karigar" value="Stock in Hand Karigar">
		<div id="left-div-karigar-result"></div>
		<input type="button" id="show-material-in-hand-user" value="Stock in Hand User">
		<div id="left-div-user-result"></div>
	</div>
	<div id="mid-div">
	<div id="job-details-div">PHASE II</div>
	<div id="job-send-report"></div>
	</div>
	<div id="right-div">
		<input type="button" value="Show Jobs" id="show-jobs"/>
		<input type="text" placeholder="JOB ID" id="job-id-special"/>
		<input type="button" value="GO" id="go"/>
		<div id="jobs-div"></div>
	</div>
	<div id="result-div"></div>
</div>
<div id="karigar-balance-div" style="overflow:scroll; height:400px;">
	<input type="submit" id="karigar-balance" value="Show Karigar Balance"/>
	<div id="all-karigar-balance-div"></div>
	<input type="button" onclick="tableToExcel('stock_at_all_employee', 'W3C Example Table')" value="Export to Excel">
</div>
<script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>