@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.paymentwall_heading', 0 )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
	    
      <div class="col-md-12 section-first-col">
        <div class="row"> 
	        
	        <div class="col-md-12 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.paymentwall_pingback_url')}}</p>
                  
                  <div class="form-group">
                     <label class="package-label"> {{{url('paymentwall_charge')}}}</label>
                  </div>
               </div>
               <div class="col-md-12 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.paymentwall_custom_parameter')}}</p>
                  
                  <div class="form-group">
                     <label class="package-label">{{trans('admin.paymentwall_custom_parameter_text')}}</label>
                  </div>
               </div>

            
            <form action = "{{{url('admin/pluginsettings/paymentwall')}}}" method = "POST" id = "set-paymentwall-form">
                {!! csrf_field() !!}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.paymentwall_title', 0 )}}</p>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.paymentwall_field', 0 )}}</label>
                     <input type="text" id = "paymentwall_public_key" placeholder="{{trans_choice('admin.paymentwall_holder', 0 )}}" value = "@if(isset($paymentwall_public_key)){{{$paymentwall_public_key}}}@endif" name = "paymentwall_public_key" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.paymentwall_field', 1 )}}</label>
                     <input type="text" id = "paymentwall_private_key" placeholder="{{trans_choice('admin.paymentwall_holder', 1 )}}" value = "@if(isset($paymentwall_private_key)){{{$paymentwall_private_key}}}@endif" name = "paymentwall_private_key" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="button" id = "set-paymentwall-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0 )}}</button>
               </div>
            </form>
            
       
			@foreach($payment_packages as $payment)
         <div class="col-md-12 user-dropdown-col user-ads-custom">
            <div class="table-responsive">
              <div class="col-md-12 col-table-inside ads-col-tableinside">
                  <p class="users-text">{{{$payment->name}}} {{trans('admin.packages')}}</p>
              </div>
              
                  <table class="table" id="user-table">
                         <thead>
                            <tr>
                               <th>{{trans_choice('admin.fortumo_pack',0)}}</th>
                               <th> {{trans('admin.status')}}</th>
                               <th> {{trans_choice('admin.fortumo_pack',3)}}</th>
                            </tr>
                         </thead>
                         <tbody>

                         @if(count($payment->packages) > 0)
							 @foreach($payment->packages as $pack)
								
								 	<tr>
		                               <td>{{{$pack->name}}}</td>
		                              
		                               <td>
			                            <label class="switch">
					                        <input class="switch-input switch-packages debug-mode-switch" type="checkbox" data-item-id="{{{ $pack->id }}}" data-item-name = "{{{$payment->name}}}" @if($pack->status == 'true') checked @endif/>
					                        <span class="switch-label"></span> 
					                        <span class="switch-handle"></span>
					                    </label>
		                               </td>
		                               <td>
		                                {{{$payment->name}}}_{{{$pack->amount}}}_{{{$pack->id}}}
		                               </td>
		                            </tr>
								</form>
	                         @endforeach    
                         @else
                         	<tr >
                            	<td colspan = "8" style = "text-align : center; color : red">{{trans_choice('admin.no_record',1)}}</td>
                         	</tr>
                         @endif    

                         </tbody>
                      </table>
                     
                      
                  
              </div>
          </div>
		  @endforeach
           
        </div>
      
      
</div>
</section>
</div>









@endsection
@section('scripts')
<script>
   
$('#set-paymentwall-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-paymentwall-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/paymentwall')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});

   

 $(".switch-packages").change(function(){
        
          var name = $(this).data('item-name');
          
          var id= $(this).data('item-id');
          
          if(this.checked){
          	
          	url = "{{{ url('/admin/add_gateway_package') }}}";
           }
          else {
            
             url = "{{{ url('/admin/remove_gateway_package') }}}";
            
          }
           data={package_id:id,type:name,gateway:"paymentwall"};
            $.ajax({
		          type: "POST",
		          url: url,
		          data: data,
		          success: function(msg){
		                
		               toastr.success('Saved');                                     
		                
		          },
		          error: function(XMLHttpRequest, textStatus, errorThrown) {
		                toastr.error("{{{trans_choice('app.error',1)}}}");
		          }
                                      
            });
    
        
        });
   
   
</script>

<style type="text/css">
   
.admin-create-div{
   width : 100%;
}

.block-switch{
   margin-left: 108%;
    margin-top: -21px;
}
.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}

</style>
@endsection