<div role="tabpanel" class="tab-pane verotelcredit credit-card-box" name="verotel">
						   				   
						   				   <form  class="form-horizontal paymentGateway" action="{{{url('/verotel')}}}" method="POST" id="verotel">
							   				   {!! csrf_field() !!}
							   				   
							   				    <div class="select-points container">
													<select class="selectpicker form-control packages"  name = "package">
																												
														
												      </select>
						  					  	 </div>
						  					  	 
							   				   
							   				    <input type="hidden" class="feature" name="feature" value="">
							   				    <input type="hidden" class="description" name="description" value="">
							   				    <input type="hidden" class="metadata" name="metadata" value="">
  					  	 <input type="hidden" id="paypal-amount" class="amount-form" name="amount" value="">
						 <input type="hidden" name="packid" class="packageId" value=""/>
							   				     
									    		<div class="form-group">
										    	
					                        </div>
					                            <div class="form-group">
						                            
						                            <button type="submit" class="btn btn-success pay">{{{trans_choice('app.paynow',1)}}}</button>

												 					                              
												</div>
						   				   </form>	
								</div>

@section('plugin-scripts')

@parent


<script>
	function verotel($form)
	{
		
		$form.get(0).submit();
		return;
		
		
	}
	
</script>
@endsection
