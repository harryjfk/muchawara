<script>
Plugin.addHook('payment_modal_open', function(feature){
   
    $("#bank_transfer_processing_error_div").hide();

    var data = {
        _token : "{{csrf_token()}}",
        feature : feature
    };
    var url = "{{url('bank-transfer/status')}}";
    $.post(url, data, function(response){
        if(response.success_type == "PENDING_PROCESS") {
            $("#bank_transfer_processing_error_div").show();
            $("#bank_transfer_processing_error_div > span").text(response.success_text);
        }
    });

});
</script>