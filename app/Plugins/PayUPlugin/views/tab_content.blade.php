<div role="tabpanel" class="tab-pane payucredit credit-card-box" name="payu">

	<form  class="form-horizontal paymentGateway" action="{{$payuPostURL}}" method="POST" id="payu">		
							   				  	
		<div class="select-points container">
			<select class="selectpicker form-control packages" name = "package"></select>
		</div>

		<div class="container">
			<select class="form-control" name = "accountId">
			@foreach($countryCodes as $code)
				<option value="{{$code->account_id}}" @if($code->country == $auth_user->country) selected @endif>{{$code->country}}</option>
			@endforeach
			</select>
		</div>


	    <input type="hidden" class="feature" name="feature" value="">
	    <input type="hidden" class="description" name="description" value="">
	    <input type="hidden" class="metadata" name="metadata" value="">
		<input type="hidden" class="amount-form" name="amount" value="">
		<input type="hidden" name="packid" class="packageId" value=""/>
		
		<input name="tax" type="hidden"  value="0"  >
		<input name="extra1" type="hidden"  value="">
		<input name="extra2" type="hidden"  value="">
		<input name="merchantId" type="hidden"  value="{{$payuSettings['payu_merchant_id']}}">
  		<input name="taxReturnBase" type="hidden"  value="0" >
  		<input name="currency" type="hidden"  value="{{$currency}}" >
  		<input name="referenceCode" type="hidden" value="">
  		<input name="signature" type="hidden" value="">
  		<input name="buyerEmail" type="hidden"  value="{{$auth_user->username}}">
  		<input name="responseUrl" type="hidden"  value="{{$responseURL}}">
  		<input name="confirmationUrl" type="hidden" value="{{$confirmationURL}}">
							   	
		<div class="form-group"></div>
		<div class="form-group">
			<button type="submit" class="btn df-button--paypal df-button--large">{{trans('app.check_out_with')}} 
				<img src="" class="payu-tab-logo" style="vertical-align: bottom" width="79" height="24">
			</button>
		</div>
	</form>	
</div>

@section('plugin-scripts')
@parent
<script>

	function buildJSONMetadata()
	{
		var object = {
			"userID" : "{{$auth_user->id}}",
			"packageID" : $("#payu > input[name=packid]").val(),
			"feature" : $("#payu > input[name=feature]").val()
				
		};
		$("#payu > input[name=extra1]").val(JSON.stringify(object));
	}
	
	function payu($form)
	{
		var data = $form.serializeArray();
		var url = "{{url('plugins/payu/get-reference-and-signature')}}?returbackuri={{request()->getRequestURI()}}";
		$.post(url, data, function(response){
			

			if(response.status == "success") {
				$("#payu > input[name=referenceCode]").val(response.referenceCode);
				$("#payu > input[name=signature]").val(response.signature);

				$("#payu > input[name=extra2]").val($("#payu > input[name=metadata]").val());
				buildJSONMetadata();


				$("#payu > input[name=description]").val($("#payu .selectpicker option:selected").text());
				$("#payu > input[name=feature]").remove();
				$("#payu > input[name=metadata]").remove();
				$("#payu > input[name=packid]").remove();
				$("#payu .select-points").remove();

				$form.get(0).submit();
			} else {
				toastr.error("{{trans('app.error')}}");
			}


		});

		return;
	}
	
</script>
@endsection