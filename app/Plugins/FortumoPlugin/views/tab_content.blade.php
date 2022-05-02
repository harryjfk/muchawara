<div role="tabpanel" class="tab-pane  fortumocredit credit-card-box" name="fortumo">
						   				   
	<form action= "{{{url('/fortumo')}}}" class="form-horizontal paymentGateway" method="POST" id="fortumo">
	
			{!! csrf_field() !!}
			
			<div class="select-points container">
							<select class="selectpicker form-control packages" name = "package">
								
								
								
								
								
								
						      </select>
  					  	 </div>
  					  	 
  					  	 
  					  	   <input type="hidden" class="feature" name="feature" value="">
							   			<input type="hidden" class="description" name="description" value="">	    
							   				    <input type="hidden" class="metadata" name="metadata" value="">
  					  	 <input type="hidden" id="paypal-amount" class="amount-form" name="amount" value="">
						 <input type="hidden" name="packid" class="packageId" value=""/>

			
			<a id="fmp-button" href="" style="visibility: hidden" rel=""><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="https://assets.fortumo.com/fmp/fortumopay_150x50_red.png" width="150" height="50" alt="Mobile Payments by Fortumo" border="0" />   </a>
			
			
			 <div class="form-group">
												
				                        	      
				                        	      <input type="submit"  style="
    
    background-image: url('https://assets.fortumo.com/fmp/fortumopay_150x50_red.png');
    border: none;
    width: 24%;
    background-repeat: no-repeat;
    position: absolute;
    background-color: transparent;
    color: transparent;
    right: 38%;
    bottom: 39px;
    background-size:contain;
    height: 42px; 
">
<!-- 				                        	      <img  id="paypal-pay2" class=" col-sm-offset-8 paymentCallbackSubmit"src="@asset('images')/paypal.png"> -->
				                        	    
					                              
												</div>

		
	</form>
</div>






@section('plugin-scripts')

@parent


<script>
	
	// var pending= "{{{Session::get('pending')}}}";
	// if(pending=='1')
	// {
	// 	$('#processing-modal').modal('show');
		
		
		
	// }
	
	
	// $('.paymentDone').on('click',function(){
		
	// 	//api call to make payment processing 'seen'
	// 	$.ajax({
	// 		  type: "GET",
	// 		  url: 'fortumo/clearNotifs',
	// 		  data: {},
	// 		  success: function(msg){
				  
				  				  
				  
			       
	// 		  },
	// 		  error: function(XMLHttpRequest, textStatus, errorThrown) {
	// 		        toastr.error("{{{trans_choice('app.error',1)}}}");
	// 		  }
	// 	});
		
		
	// });
	
	
	function fortumo($form)
	{
		
		
/*
		var arry = $form.serializeArray();
		
		var feature = _.findWhere(arry,{name:'feature'});
		
		var packId = _.findWhere(arry,{name:'packid'});
		
		
		if(feature.value=="superpower_callback"){
			
			var invisible= _.findWhere(arry,{name:'invisible'}).value;
			
			url= "{{{ url('/fortumo') }}}"+'/?feature='+feature.value+'&packId='+packId.value+'&invisible='+invisible;
		}	
		else
		{
			url= "{{{ url('/fortumo') }}}"+'/?feature='+feature.value+'&packId='+packId.value;
		}
			
			
			$.ajax({
			  type: "GET",
			  url: url,
			  data: {},
			  success: function(msg){
				  
				  //toastr.success('Success');
				  
				  $('#fmp-button').attr('href',msg.str);
				  
				  $('#fmp-button').attr('rel',msg.rel);
				  
				  
				  window.location.href = msg.str;
				  
				  
			       
			  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			        toastr.error("{{{trans_choice('app.error',1)}}}");
			  }
			});
*/
		
		
		$form.get(0).submit();
		return;
		
		
	}
	
	
	
	
	
	
</script>


@endsection