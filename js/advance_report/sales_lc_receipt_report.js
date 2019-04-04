$(function () {
    $('#working-div').on('click','#show-lc-receipt-details',function(event){
        event.preventDefault();
        var request=$.ajax({
            type:'get',
            url: base_url+"/advance_report_controller/show_lc_receipt_details",
            data: {lc_receipt_id: $('#lc-receipt-number').val()},//end of data
            beforeSend:function(){},
            success: function(data, textStatus, xhr) {
                $('#report-div').html(data);
            }//end of success
        });//end of post request
    });


    $('#working-div').on('click','.print-div',function(){
        var printTitle=$('.print-title').html();
        var contents = $("#report-div").html();

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>'+printTitle+' </title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
        frameDoc.document.write('<link href="'+site_url+'/css/gold_receipt_style.css" rel="stylesheet" type="text/css" media="all" />');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    });//end of printbutton
});
