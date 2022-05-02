<div role="tabpanel" class="tab-pane bank_transfer credit-card-box" name="bank_transfer">
    <form  class="form-horizontal paymentGateway" action="{{url('bank-transfer/submit/details')}}" method="POST" id="bank_transfer_form" enctype="multipart/form-data">
        {!!csrf_field()!!}
        <div class="select-points container" style="padding-left:10px;padding-right:10px">
            <select class="selectpicker form-control packages" name = "package"></select>
        </div>
        <label>{{trans('BankTransferPlugin.bank_details')}}</label>
        {!!$details!!}
        <br>
        <div class="alert alert-info" id="bank_transfer_processing_error_div" style="margin-left: 10px;margin-right: 10px;">
            <strong>{{trans('BankTransferPlugin.info')}}!</strong> <span></span>
        </div>
        <div style="margin-left: 10px;margin-right: 10px;border: 1px solid black;width: auto" class="table-responsive">
            <table class="table" style="margin: 0px; color: black;width:100%">
                <tbody>
                    <tr>
                        <td style="padding:5px;text-align:left">{{trans('BankTransferPlugin.transaction_id_label')}}</td>
                        <td style="padding:5px"><input type="text" name="transaction_id" placeholder="{{trans('BankTransferPlugin.transaction_id_placeholder')}}"></td>
                    </tr>
                    <tr>
                        <td style="padding:5px;text-align:left">{{trans('BankTransferPlugin.choose_file_label')}}</td>
                        <td style="padding:5px;display:inline-block;margin:0 auto"><input type="file" name="details_file" style="display: inline-block;"></td>
                    </tr>
                    <tr>
                       <td colspan="2" style="color:red;padding:5px">*{{trans('BankTransferPlugin.suppoerted_file_extensions')}} : {{$file_types}}</td> 
                    </tr>
                </tbody>
            </table>
        </div>
        
        <input type="hidden" class="feature" name="feature" value="">
        <input type="hidden" class="description" name="description" value="">
        <input type="hidden" class="metadata" name="metadata" value="">
        <input type="hidden" class="amount-form" name="amount" value="">
        <input type="hidden" name="packid" class="packageId" value=""/>
        <input name="currency" type="hidden"  value="{{$currency}}" >
        <div class="form-group" style="margin-top: 10px">
            <button type="submit" id="bank_transfer_submit" class="btn df-button--paypal df-button--large">{{trans('BankTransferPlugin.submit')}}
            </button>
        </div>
    </form>
</div>
@section('plugin-scripts')
@parent
<script>
    function bank_transfer_form($form)
    {	
    	/*$form.get(0).submit();*/
    	return;	
    }
    
    $(document).ready(function(){
    
    
    	$('#bank_transfer_form').submit(function(e){
        	
        	e.preventDefault();
            
        	if($("input[name=details_file]")[0].files[0] == undefined) {
        		toastr.error("{{trans('BankTransferPlugin.choose_file_error')}}");
        		return;
        	}
    
        	var data = new FormData(this);
        	data.append('details_file', $("input[name=details_file]")[0].files[0]);
        	$.ajax({
          		url: '{{url('bank-transfer/submit/details')}}',
          		type: 'POST',
          		data: data,
          		processData: false,
          		contentType: false,
          		success : function(response) {
          			if(response.status == "success") {
          				toastr.success(response.success_text);
          				$("#myModalPayment").modal('hide');
          			} else if(response.status == "error") {
          				toastr.error(response.error_text);
          			}
          		}
        	});
        	
      	});
    
    });
    
</script>
@endsection