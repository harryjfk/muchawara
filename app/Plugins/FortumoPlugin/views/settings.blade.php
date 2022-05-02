@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.fortumo_heading', 0 )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row"> 
               <div class="col-md-12 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.fortumo_callback')}}</p>
                  
                  <div class="form-group">
                     <label class="package-label"> {{{url('/fortumo/callback')}}}</label>
                  </div>
               </div>
               <div class="col-md-12 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.fortumo_return')}}</p>
                  
                  <div class="form-group">
                     <label class="package-label"> {{{url('/fortumo/pending')}}}</label>
                  </div>
               </div>
               <div class="col-md-12 add-creditpackage-col admin-create-div">
                  <p class="users-text">{{trans('admin.fortumo_status')}}</p>
                  <form action = "{{{url('admin/pluginsettings/fortumo_mode')}}}" method = "POST" id = "set-fortumo-mode-form">
                    {!! csrf_field() !!}    
                      <div class="form-group">
                         <select name = "fortumo_mode" class="form-control input-border-custom select-custom">
                            @if($fortumo_mode == 'true')
                                <option value = "false">{{trans_choice('admin.fortumo_mode', 0 )}}</option>
                                <option value = "true" selected>{{trans_choice('admin.fortumo_mode', 1 )}}</option>
                            @else
                                <option value = "false" selected>{{trans_choice('admin.fortumo_mode', 0 )}}</option>
                                <option value = "true">{{trans_choice('admin.fortumo_mode', 1 )}}</option>
                            @endif                          
                        </select>
                      </div>
                  <button type="submit" id = "set-fortumo-mode-btn" class="btn btn-info btn-addpackage btn-custom"> {{trans_choice('admin.save', 0 )}}</button>
                </form>
               </div>
         
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
                               <th> {{trans_choice('admin.fortumo_pack',3)}}</th>
                               <th> {{trans_choice('admin.fortumo_pack',4)}}</th>
                               <th> {{trans('admin.status')}}</th>
                               <th></th>
                            </tr>
                         </thead>
                         <tbody>

                         @if(count($payment->packages) > 0)
							 @foreach($payment->packages as $pack)
								<form action = "{{{url('admin/pluginsettings/fortumo_packages')}}}" method = "POST" class = "set-fortumo-credits-form">
								 	{!! csrf_field() !!}
								 	<input type="hidden" name="id" value="{{{$pack->id}}}"></input>
								 	<input type="hidden" name="name" value="{{{$payment->name}}}"></input>
								 	<tr>
		                               <td>{{{$pack->name}}}</td>
		                               <td><input type="text" name="service_id" style="color:black" value="{{{$pack->service_id}}}" ></input></td>
		                               <td><input type="text" name="secret_key" style="color:black" value="{{{$pack->secret_key}}}" ></input></td>
		                               <td>
			                            <label class="switch">
				                        	<input class="switch-input switch-packages debug-mode-switch" type="checkbox" name="status" @if($pack->status == 'true') checked @endif/>
											<span class="switch-label"></span> 
											<span class="switch-handle"></span>
				                        </label>
		                               </td>
		                               <td>
		                                <button type="submit"  class="btn btn-info btn-addpackage btn-custom set-fortumo-credits-btn">{{trans_choice('admin.save', 0 )}}</button>
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
   
$('#set-fortumo-mode-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-fortumo-mode-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/fortumo_mode')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});
 
   
</script>

<script>
	
	$('.set-fortumo-credits-btn').on('click',function(e){
    e.preventDefault();
    
    var parent = $(this).parent().parent();

		var el_serviceId= parent.find("input[name=service_id]");
		
		var el_secretKey= parent.find("input[name=secret_key]");
		
		var form_el = parent.prevUntil('.set-fortumo-credits-form').last().prev();
		
		
		
		if(!el_serviceId.val() || !el_secretKey.val())
		{
			toastr.error("{{{trans('admin.fortumo_secret_service_required')}}}");
			return false;
			
		}
		else
		{
			var data = form_el.serializeArray();
   $.post("{{{url('admin/pluginsettings/fortumo_packages')}}}", data, function(response){

        if(response.status == 'success')
           toastr.success(response.message);
      else if (response.status == 'error')
            toastr.error(response.message);
			 });
		}

     

  

});






   
/*
$('#set-fortumo-credits-btn').click(function(e){
    e.preventDefault();


     var data = $('#set-fortumo-credits-form').serializeArray();
   $.post("{{{url('/admin/pluginsettings/fortumo_credits')}}}", data, function(response){

        if(response.status == 'success')
           toastr.success(response.message);
      else if (response.status == 'error')
            toastr.error(response.message);

   });

});
*/

// $('#set-fortumo-superpower-btn').click(function(e){
//     e.preventDefault();

//     var data = $('#set-fortumo-superpower-form').serializeArray();
//     $.post("{{{url('/admin/pluginsettings/fortumo_superpower')}}}", data, function(response){

//         if(response.status == 'success')
//             toastr.success(response.message);
//         else if (response.status == 'error')
//             toastr.error(response.message);

//     });

// });



$("#feature").on('change',function(){

 if($('#feature option:selected').val()=='superpower')
 {

  $.get("{{{url('/getSuperpower')}}}", function(response){

      console.log(response);

      $('.setpackage').show();
      $("#packId option").remove();

      for(i=0;i< response.length;i++)
      {

          $('#packId').append("<option value='"+response[i].id+"'>"+response[i].package_name+"</option>");

      }

      

    });


 }
 else if($('#feature option:selected').val()=='credits')
 {
    $.get("{{{url('/getCredits')}}}", function(response){

      console.log(response);

      $('.setpackage').show();

      $("#packId option").remove();

      for(i=0;i< response.length;i++)
      {

          $('#packId').append("<option value='"+response[i].id+"'>"+response[i].packageName+"</option>");

      }

    });


 }

 else
 {

    $(".setpackage").hide();
 }
   

})

   
</script>

<style type="text/css">
   
.admin-create-div{
   width : 100%;
}

.block-switch{
   margin-left: 108%;
    margin-top: -21px;
}

.section-first-col{
    min-height: 0px;
}

</style>
@endsection
