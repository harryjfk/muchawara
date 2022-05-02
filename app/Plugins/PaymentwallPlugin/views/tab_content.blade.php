<!--
<div role="tabpanel" class="tab-pane  paymentwallcredit credit-card-box" name="paymentwall">
    <form action= "" class="form-horizontal paymentGateway brick-creditcard-form" method="POST" id="paymentwall2">
        {!! csrf_field() !!}
        <input name="custom_parameter" type="hidden" value="custom_value"/>
        <div class="form-group" style="">
            <div class="col-md-4">
                <input type="hidden" name="url" value="/paymentwall_charge"/> 
                 <input type="hidden" class="feature" name="feature" value="">
							   				    
							   				    <input type="hidden" class="metadata" name="metadata" value="">
  					  	 <input type="hidden" id="paypal-amount" class="amount-form" name="amount" value="">
						 <input type="hidden" name="packid" class="packageId" value=""/>
                
            </div>
        </div>
        <div class="form-group" style="">
            <div class="col-md-4">
               
            </div>
        </div>
        <div class="form-group">
            <label for="inputType" class="col-md-3 control-label">{{{trans_choice('app.card_details',0)}}}.</label>
            <div class="col-md-8">
               
                 <input  data-brick="card-number" value=""
                                            type="tel"
                                            class="form-control card-number"
                                            name="cardNumber"
                                            placeholder="{{{trans('app.card_no')}}}"
                                            autocomplete="cc-number"
                                            required autofocus 
                                        />

            </div>
        </div>
        <div class="form-group">
            <label for="inputType" class="col-md-3 control-label">{{{trans_choice('app.card_details',1)}}}</label>

            <div class="col-md-4">


<input 
                                        type="tel" 
                                        class="form-control" 
                                        name="cardExpiry"
                                        placeholder="MM / YY"
                                        autocomplete="cc-exp"
                                        required 
                                    />

            </div>
        </div>
        <div class="form-group">
            <label for="inputType" class="col-md-3 control-label">{{{trans_choice('app.card_details',2)}}}</label>
            <div class="col-md-4">


 <input 
                                        type="tel" data-brick="card-cvv" value=""
                                        class="form-control card-cvv"
                                        name="cardCVC"
                                        placeholder="CVC"
                                        autocomplete="cc-csc"
                                        required
                                    />

            </div>
        </div>
        <button type="submit" class="btn btn-success pay">{{{trans_choice('app.paynow',1)}}}</button>
    </form>
</div>
@section('plugin-scripts')
@parent
<style type="text/css">
    .card-number, .card-exp-month, .card-exp-year, .card-cvv{
    width : 100%;
    height: 34px;
    padding-top: 6px;
    padding-right: 12px;
    padding-bottom : 6px;
    padding-left: 12px;
    font-size: 14px;
    color: black; 
    }
</style>
<script>
	
	function paymentwall2($form)
    {
    	
    	// using jQuery
    	 
    	 var brick = new Brick({
    	   public_key: '{{{$paymentwall_public_key}}}',
    	   form: { formatter: true }
    	 }, 'custom');
    	 
    	 var expiry = $form.find('[name=cardExpiry]').payment('cardExpiryVal');
		 var ccData = {
	        card_number: $form.find('[name=cardNumber]').val().replace(/\s/g,''),
	        card_cvv: $form.find('[name=cardCVC]').val(),
	        card_expiration_month: expiry.month, 
	        card_expiration_year: expiry.year
	    };

    	 
    	  brick.tokenizeCard(ccData, function(response) {
    		   console.log(response);
    	     if (response.type == 'Error') {
    	       // handle errors
    	     } else {
    	     	console.log(Brick.getFingerprint());
    	       $form.append($('<input type="hidden" name="brick_token"/>').val(response.token));
    	       $form.append($('<input type="hidden" name="brick_fingerprint"/>').val(Brick.getFingerprint()));
    	       //alert('form getting submitted');
    	       $form.get(0).submit();
    	       
    	       return $form;
    	     }
    	   });
    	
    	return false;
    
    	
    	 
    	
    }

</script>	
<script src="https://api.paymentwall.com/brick/brick.1.4.js"> </script>

<script type="text/javascript" src="@plugin_asset('PaymentwallPlugin/js/validation_paymentwall.js')"></script>


@endsection
-->

<div role="tabpanel" class="tab-pane  paymentwallcredit credit-card-box" name="paymentwall">
	
	 						<input type="hidden" class="feature" name="feature" value="">
	 						<input type="hidden" class="metadata" name="metadata" value="">
	 						<input type="hidden" class="description" name="description" value="">
  					  	    <input type="hidden" id="paypal-amount" class="amount-form" name="amount" value="">
						    <input type="hidden" name="packid" class="packageId" value=""/>
						 
						 <div class="select-points container">
							<select class="selectpicker form-control packages" name = "package">
								
								
								
								
								
								
						      </select>
  					  	 </div>
	<div class="alert alert-info defaultPackage" style="display: none">
  {{trans('app.select_package')}}
</div>
<iframe class="payment_wall_iframe" width="560" height="325" frameborder="0"></iframe>
<script>
	var payment_wall_widget_url = "https://api.paymentwall.com/api/subscription/?uid={{{ $auth_user->id }}}&widget=p1_1&email={{{ $auth_user->username }}}&key={{{ $paymentwall_public_key }}}&history[registration_date]=";
	
	
	
	if(payment_wall_loaded == undefined){
		var payment_wall_loaded = 0;
	}
	
// 	&display_goodsid[0]=credit_11
	
	var unix_timestamp = new Date('{{{ $auth_user->created_at }}}'.replace(' ', 'T')).getTime();
	
	payment_wall_widget_url = payment_wall_widget_url + unix_timestamp;
	
	
	function create_new_iframe_paymentwall(url){
		
		console.log("Testing PW");
		
		$(".payment_wall_iframe").remove();
		
		$(".paymentwallcredit").append('<iframe class="payment_wall_iframe" src="'+url+'" width="560" height="325" frameborder="0"></iframe>');
	}
	
	$(function(){ 
		
		
		
		if(payment_wall_loaded == 0){ 
		
		$( "select.packages" ).change(function () { 
	
					
					
					
					var refill_package_id = $(this).find(':selected').data('package-id');
					
					var feature = $('.feature').val();
					
					
					var amount=$(this).find(':selected').data('amount');
					
				//var refill_package_id = $(this).find(':selected').data('package-id');
					if(refill_package_id!=undefined)
					{
						$('.defaultPackage').hide();
						
						var display_packages = [];
						
						$("div[name='paymentwall'] select.packages option").each(function(index){
							
							if($($(this)[0]).data('package-id') != undefined)
							{
								display_packages.push(
								
								'&display_goodsid['+(parseInt(index)-1)+']='+feature+'_'+$($(this)[0]).data('amount')+'_'+$($(this)[0]).data('package-id')
								
								
								);
								}
							
						})
						
						
						var url_paymentWall= payment_wall_widget_url+display_packages.join("")+"&default_goodsid="+$('.feature').val()+"_"+amount+'_'+refill_package_id+"&metadata="+$('.metadata').val();
												
						console.log(url_paymentWall.replace(/\"/g, ""));
						create_new_iframe_paymentwall(url_paymentWall.replace(/\"/g, ""));
					}
					else
					{
						
						$('.defaultPackage').show();
						$('.payment_wall_iframe').hide();
						
					}	
			
	
	});

	setTimeout(function(){
		var default_sp_id = $( "select.packages" ).find(':selected').data('package-id');
		
		if(default_sp_id != undefined)
		{
			create_new_iframe_paymentwall(payment_wall_widget_url+"&default_goodsid="+$('.feature').val()+"_"+default_sp_id );
		}
		 else{
			 
			 $('.defaultPackage').show();
			 
			 $('.payment_wall_iframe').hide();
			 
		 }	
		}, 0);

	
		
		
$( "#lunch" ).change(function () {
	  



		var refill_package_id = $(this).find(':selected').data('package-id');
	create_new_iframe_paymentwall(payment_wall_widget_url+"&default_goodsid=c_"+refill_package_id);

});

	
	payment_wall_loaded = 1;
	
	}
	
});

</script>
</div>