@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">

.admin-create-div{
   width : 100%;
 }
.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans('admin.twilio_settings_header')}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST" id = "set-twilio-form">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.twilio_settings_title')}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans('admin.twilio_account_sid')}}</label>
                     <input type="text"  placeholder="{{trans('admin.twilio_account_sid_placeholder')}}" id = "account_sid" name = "account_sid" value = "{{{$account_sid}}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('admin.twilio_auth_token')}}</label>
                     <input type="text" placeholder="{{trans('admin.twilio_auth_token_placeholder')}}" id = "auth_token" name = "auth_token" value = "{{{$auth_token}}}" class="form-control  input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">{{trans('admin.twilio_from_number')}}</label>
                     <input type="text" placeholder="{{trans('admin.twilio_from_number_placeholder')}}" id = "from_number" name = "from_number" value = "{{{$from_number}}}" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="button" id = "set-twilio-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                  
               </div>
            </form>
     
         </div>
      </div>
</div>
</section>
</div>


@endsection
@section('scripts')

<script>
$(document).ready(function(){

    $("#set-twilio-btn").on("click", function(){

        var URL = "{{url('admin/settings/twilio/save')}}";
        var data = {
            account_sid : $("#account_sid").val(),  
            auth_token : $("#auth_token").val(),  
            from_number : $("#from_number").val(),  
            _token : "{{csrf_token()}}"
        };


        $.post(URL, data, function(response){
            if(response.status === "success") {
                toastr.success('{{trans_choice('admin.set_status_message', 0)}}');
            }
        });

    });

});
   
</script>

@endsection