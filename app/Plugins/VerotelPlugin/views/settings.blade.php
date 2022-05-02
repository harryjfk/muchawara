@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.verotel_heading', 0 )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row"> 
            <div class="col-md-12 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.verotel_return_url')}}</p>
                  
                  <div class="form-group">
                     <label class="package-label"> {{{url('/verotel/success')}}}</label>
                  </div>
               </div>
            <form action = "{{{url('admin/pluginsettings/verotel')}}}" method = "POST" id = "set-verotel-form">
                {!! csrf_field() !!}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.verotel_title', 0 )}}</p>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.verotel_field', 0 )}}</label>
                     <input type="text" id = "verotel_shop_id" placeholder="{{trans_choice('admin.verotel_holder', 0 )}}" value = "@if(isset($verotel_shop_id)){{{$verotel_shop_id}}}@endif" name = "verotel_shop_id" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.verotel_field', 1 )}}</label>
                     <input type="text" id = "verotel_signature_key" placeholder="{{trans_choice('admin.verotel_holder', 1 )}}" value = "@if(isset($verotel_signature_key)){{{$verotel_signature_key}}}@endif" name = "verotel_signature_key" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="button" id = "set-verotel-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0 )}}</button>
               </div>
            </form>
            
         </div>
         
         @foreach($payment_packages as $payment)
         <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{$payment->name}}} {{trans('admin.packages')}}</p>
                
                    @foreach($payment->packages as $pack)
                    	<div class="form-group">
	                    	<label class="package-label">{{{$pack->name}}}</label>
		                    <label class="switch">
		                        <input class="switch-input switch-packages debug-mode-switch" type="checkbox" data-item-id="{{{ $pack->id }}}" data-item-name = "{{{$payment->name}}}" @if($pack->status == 'true') checked @endif/>
		                        <span class="switch-label"></span> 
		                        <span class="switch-handle"></span>
		                    </label>
		                </div>
                    @endforeach	
                
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
   
$('#set-verotel-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-verotel-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/verotel')}}}", data, function(response){

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
           data={package_id:id,type:name,gateway:"verotel"};
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