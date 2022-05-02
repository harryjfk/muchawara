@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans('custom::custom.invite_settings' )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
	       <div class="row">
                <form >
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('custom::custom.invites')}}</p>
                       
                        <div class="form-group">
                            <label class="package-label">{{{trans('custom::custom.invites_enable_title')}}}</label>
                            <label class="switch">
                            <input class="switch-input invite-enable-switch" type="checkbox" @if($inviteenabled) checked @endif/>
                            <span class="switch-label" ></span> 
                            <span class="switch-handle"></span> 
                            </label>
                        </div>
                        <button type="button" id = "save-invite-settings" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                    </div>
                </form>
            </div>
	      
	      
         <div class="row"> 

         <div class="col-md-12 user-dropdown-col user-ads-custom">
            <div class="table-responsive">
              <div class="col-md-12 col-table-inside ads-col-tableinside">
                  <p class="users-text">{{trans('custom::custom.list_users_invite')}}</p>
              </div>
              
                  <table class="table" id="user-table">
                         <thead>
                            <tr>
                               <th>{{trans('custom::custom.email')}}</th>
                               <th> {{trans_choice('admin.date',0)}}</th>
                               <th> {{trans('admin.status')}}</th>
                               <th></th>
                            </tr>
                         </thead>
                         <tbody>

                         @if(count($users) > 0)
							 @foreach($users as $user)
								<form action = "{{{url('/admin/invite/accept')}}}" method = "POST" class = "set-invite-form">
								 	{!! csrf_field() !!}
								 	<input type="hidden" name="id" value="{{{$user->id}}}"></input>
								 	<tr>
		                               <td>{{{$user->email}}}</td>
		                               <td>{{{$user->created_at}}}</td>
		                               <td>{{{$user->status}}}</td>
		                               
		                               <td>
			                               @if($user->status == "waiting")
		                                <button type="submit"  class="btn btn-info btn-addpackage btn-custom set-invite-btn">{{trans('custom::custom.accept')}}</button>
											@endif
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

        </div>
      </div>
  </section>

</div>



@endsection
@section('scripts')

<script>
   



$('#save-invite-settings').click(function(){
    
        var invite_enabled = ($('.invite-enable-switch').is(':checked')) ? 1 : 0;
            
        var data = {
            invite_enabled : invite_enabled,
            _token : "{{csrf_token()}}"
        };

        
        $.post("{{{url('/admin/invite/active_deactive')}}}", data, function(response){
    
            if(response.status == 'success') {
                toastr.success('{{trans_choice('admin.set_status_message', 0)}}');
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

.section-first-col{
    min-height: 0px;
}

</style>
@endsection
